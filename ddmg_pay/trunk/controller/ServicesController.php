<?php
/**
 * 长沙银行回调接口
 * @author zhangkui
 *
 */
class ServicesController extends Controller {

    public function handle($params = array())
    {
        Log::bcsError('403 forbidden' . $params[0]);
    }

    public function request($xml)
    {
        Log::bcsNotice('Bank callback request data ' . var_export($xml ,true));
       /*  if(!$this->checkSignData($xml)){
            Log::bcsError('validate signData error');
            return $this->response($xml,'00000001','验证签名失败','通知失败');
        } */

        preg_match('/<Body>(.*)<\/Body>/is', $xml , $body);
        $reqData = json_decode(json_encode(simplexml_load_string($body[1])),true);

        $keys    = array('MCH_NO','SIT_NO','ACT_TIME','ACCOUNT_NO');
        foreach($keys as $key){
            if(!isset($reqData[$key]) || !$reqData[$key]){
                Log::bcsError('Bank callback request data miss');
                return $this->response($xml,'00000002','数据错误','通知失败');
            }
        }

        //判断是否存在记录
        $data = $this->model('bcsRegister')->getList(array('SIT_NO' => $reqData['SIT_NO'],'ACCOUNT_NO' => $reqData['ACCOUNT_NO'],'fields' => array('id','user_id')));
        if($data['code'] !== EC_OK){
            Log::bcsError('bcsRegister getList error');
            return $this->response($xml,'00000004','拉取数据异常','通知失败');
        }
        
        if(!$data['data']){
            Log::bcsError('bcsRegister getList is empty');
            return $this->response($xml,'00000005','请求数据不存在','通知失败');
        }
        
        $register_id = $data['data']['0']['id'];
        $user_id = $data['data']['0']['user_id'];
        $data = $this->model('bcsRegister')->update(array(
            'id'         => $register_id,
            'ACT_TIME'   => date('Y-m-d H:i:s',strtotime($reqData['ACT_TIME']))
        ));
        
        //更新签约时间失败
        if($data['code'] !== EC_OK){
            Log::bcsError('bank callback update sign_time error msg('.$data['code'].')');
            return $this->response($xml,'00000006','更新签约时间失败','通知失败');
        }
        
        //拉取注册用户信息
        $customer = $this->model('bank')->getCustomerInfo($reqData['MCH_NO'],$reqData['SIT_NO']);
        if($data['code'] !== 0 ){
            Log::bcsError('bank callback get customer info error masg('.$data['msg'].')');
            return $this->response($xml,'00000007','拉取银行客户信息失败','通知失败');
        }
        $customer = $customer['data'];
        
        $data = $this->model('bcsCustmer')->getList(['MCH_NO' => $reqData['MCH_NO'],'SIT_NO' => $reqData['SIT_NO'],'fields' => ['id']]);
        if($data['code'] !== EC_OK){
            Log::bcsError('bank callback bcsCustomer getList error msg('.$data['msg'].')');
            return $this->response($xml,'00000008','api拉取客户信息失败','通知失败');
        }
        //处理数组
        $customer['MBR_ADDR']   = $customer['MBR_ADDR'] ? $customer['MBR_ADDR']   :'';
        $customer['MBR_TELENO'] = $customer['MBR_TELENO'] ?$customer['MBR_TELENO']:'';
        $customer['MBR_PHONE']  = $customer['MBR_PHONE'] ? $customer['MBR_PHONE'] :'';
        
        if($data['data']){
            $customer['id'] = $data['data'][0]['id'];
            $data = $this->model('bcsCustmer')->update($customer);
            if($data['code'] !== EC_OK){
                Log::bcsError('bank callback update customer info error msg('.$data['msg'].')');
            }
        }else{
            $customer['user_id']       = $user_id;
            $customer['add_timestamp'] = date('Y-m-d H:i:s');
            $data = $this->model('bcsCustmer')->create($customer);
            if($data['code'] !== EC_OK){
                Log::bcsError('bank callback create customer info error msg('.$data['msg'].')');
            }
        }
       
        return $this->response($xml,'00000000');
    }
    
    protected function getRealIp() {
        $ip=false;
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ('^(10|172\.16|192\.168)\.', $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    protected function checkSignData($xml)
    {
        if(preg_match('/<SignData>(.*)<\/SignData>/is', $xml , $sign) != 1){
            Log::bcsNotice('preg_match SignData error');
            return false;
        }

        if(preg_match('/<Body>(.*)<\/Body>/is', $xml , $body) != 1){
            Log::bcsNotice('preg_match Body error');
            return false;
        }

        $sign = hex2bin($sign[1]);
        $data = '<Body>'.$body[1].'</Body>';
        return 1 == openssl_verify($data,$sign,file_get_contents('../security/008.08.cer'),OPENSSL_ALGO_SHA1);
    }
    protected function signData($data)
    {
        openssl_pkcs12_read( file_get_contents('../security/008.08.pfx'), $certs, '952789');
        openssl_sign($data, $signMsg, $certs['pkey'], OPENSSL_ALGO_SHA1); // 私钥加密
        return  strtoupper(bin2hex($signMsg)); // 转大写( 必须 )
    }

    protected function response($reqXml,$code = '00000000',$msg ='',$title = '通知成功')
    {
        $seqno = '';
        if ( preg_match('/<ExternalReference>(.*)<\/ExternalReference>/', $reqXml, $rs) ) {
            $seqno = $rs[1];
        }

        $addXml = '<RequestIp>' . $this->getRealIp() .'</RequestIp>';
        $addXml.= '<SEQNO>'.$seqno.'</SEQNO>';
        $addXml.= '<Response><ReturnCode>'.$code.'</ReturnCode><ReturnMessage>'.$msg.'</ReturnMessage></Response>';

        $resXml = preg_replace('/<\/Header>/is', $addXml . '</Header>', $reqXml);
        $resXml = preg_replace('/<Body>(.*)<\/Body>/is', '<Body><Response><IS_SUCCESS>'.$title.'</IS_SUCCESS></Response></Body>' , $resXml);
        $resXml = preg_replace('/<SignData>(.*)<\/SignData>/is', '<SignData>'.$this->signData('<Body><Response><IS_SUCCESS>'.$title.'</IS_SUCCESS></Response></Body>') . '</SignData>', $resXml);

        return $resXml;
    }
}