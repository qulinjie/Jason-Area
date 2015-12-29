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
        if(!$this->checkSignData($xml)){
            Log::bcsError('validate signData error');
            return $this->response($xml,'00000001','验证签名失败','通知失败');
        }

        preg_match('/<Body>(.*)<\/Body>/is', $xml , $body);
        $reqData = json_decode(json_encode(simplexml_load_string($body[1])),true);

        $keys    = array('MCH_NO','SIT_NO','ACT_TIME','ACCOUNT_NO');
        foreach($keys as $key){
            if(!isset($reqData[$key]) || !$reqData[$key]){
                Log::bcsError('Bank callback request data miss');
                return $this->response($xml,'00000002','数据错误','通知失败');
            }
        }

        $response = $this->model('bcsRegister')->update(array(
            'MCH_NO'     => $reqData['MCH_NO'],
            'SIT_NO'     => $reqData['SIT_NO'],
            'ACT_TIME'   => $reqData['ACT_TIME'],
            'ACCOUNT_NO' => $reqData['ACCOUNT_NO']
        ));

        if($response['code'] !== EC_OK){
            Log::bcsError('bank callback error msg('.$response['code'].')');
            return $this->response($xml,'00000003','请求异常','通知失败');
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