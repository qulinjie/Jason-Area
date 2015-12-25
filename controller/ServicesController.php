<?php
/**
 * 长沙银行回调接口
 * @author zhangkui
 *
 */
class ServicesController extends Controller {

    public function handle($params = array()) {
        if (empty($params)) {
            Log::error('ServicesController . params is empty . ');
            EC::fail(EC_MTD_NON);
        } else {
            switch ($params[0]) {
                case 'login':
                    // $this->login();
                    break;
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }

    protected function checkSign()
    {

    }

    protected function response()
    {
        $responseXML=<<<EOF
<Service>
    <Header>
        <ServiceCode>FMSCUST0001</ServiceCode>
        <ChannelId>607</ChannelId>
        <ExternalReference>370000201408210006698</ExternalReference>
        <RequestTime>20151110110925</RequestTime>
        <TradeDate>20151110</TradeDate>
        <Version>1.0</Version>
        <TermType>00000</TermType>
        <TermNo>0000000000</TermNo>
        <RequestType>0</RequestType>
        <Encrypt>0</Encrypt>
        <SignData></SignData>
        <RequestIp>127.0.0.1</RequestIp>
        <SEQNO>370000201408210006698</SEQNO>
        <Response>
            <ReturnCode>00000000</ReturnCode>
            <ReturnMessage></ReturnMessage>
        </Response>
    </Header>
        <Body>
            <Response>
                <IS_SUCCESS>通知成功</IS_SUCCESS>
            </Response>
        </Body>
    </Service>

EOF;
    }

    public function request($param) {
        Log::notice('client ip=##' . $this->get_real_ip() . '##' );
        if(!$reqXml  = simplexml_load_string($param)){
            //非XML数据
            Log::error('Bank callback request data not is XML');
            Log::error('Bank callback request data ##' . $param . '##' );
            return $this->buildSoapXml('Bank callback request data not is XML');
        }

        $keys    = array('MCH_NO','SIT_NO','ACT_TIME','ACCOUNT_NO');
        $reqData = (array) $reqXml->Body->Request;
        foreach($keys as $key){
            //缺少数据
            if(!isset($reqData[$key])|| !$reqData[$key]){
                Log::error('Bank callback request data miss');
                return $this->buildSoapXml('Bank callback request data miss');
            }
        }

        $response = $this->model('bcsRegister')->update(array(
            'MCH_NO' => $reqData['MCH_NO'],
            'SIT_NO' => $reqData['SIT_NO'],
            'ACT_TIME' => $reqData['ACT_TIME'],
            'ACCOUNT_NO' => $reqData['ACCOUNT_NO']
        ));

        if($response['code'] !== EC_OK){
            Log::error('bank callback error msg('.$response['code'].')');
            return $this->buildSoapXml($response['msg']);
        }

        return self::buildSoapXml('success');
    }
    
    public function buildSoapXml($param='') {
        $body = '<Body><Response><IS_SUCCESS>通知成功</IS_SUCCESS></Response></Body>';
        $requestIp = '<RequestIp>' . $this->get_real_ip() .'</RequestIp>';
        $seqno = '<SEQNO></SEQNO>';
        if ( preg_match('/<ExternalReference>(.*)<\/ExternalReference>/', $param, $rs) ) {
            $seqno = '<SEQNO>' . $rs[1] . '</SEQNO>';
        }
        $headerResponse = '<Response><ReturnCode>00000000</ReturnCode><ReturnMessage></ReturnMessage></Response>';
        $addXml = $requestIp . $seqno . $headerResponse ;
        $result = preg_replace('/<\/Header>/is', $addXml . '</Header>', $param);
        $result = preg_replace('/<Body>(.*)<\/Body>/is', $body , $result);
        return $result;
    }
    
    public function get_real_ip() {
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
}