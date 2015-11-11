<?php

class Callback
{

    public function __construct()
    {}

    /*
    public function request2($param)
    {
        $logFileName = 'K:/' . date('Y-m-d') . '.log';
        $myfile = @fopen($logFileName, "w");
        fwrite($myfile, date('Y-m-d H:i:s',time()) . "request=##" . $param . "##\r\n");
        $result = array(
            'result' => 'success',
            'mag' => $param
        );
        $result['date'] = date('Y-m-d H:i:s',time());
        $result = json_encode($result);
        fwrite($myfile, date('Y-m-d H:i:s',time()) . " response=##" . $result . "##\r\n");
        fclose($myfile);
        return $result;
    }
    */
    /*
    public function request($param)
    {
        date_default_timezone_set(PRC);
        
        //$logFileName = 'K:/' . date('Y-m-d') . '.log';
        $logFileName = '/alidata/www/ddmg_pay/log/' . date('Y-m-d') . '.log';
        
        $myfile = @fopen($logFileName, "w");
        fwrite($myfile, date('Y-m-d H:i:s',time()) . " request=##" . $param . "##\r\n");
        
        $result = "";
        $result .= "<Service>";
        $result .= "	<Header>";
        $result .= "		<RequestTime>" . date('Y-m-d H:i:s',time()) . "</RequestTime>";
        $result .= "	</Header>";
        $result .= "	<Body>";
        $result .= "		<Response>";
        $result .= "			<RESULT>SUCCESS</RESULT>";
        $result .= "			<MSG>成功</MSG>";
        $result .= "		</Response>";
        $result .= "	</Body>";
        $result .= "</Service>";
        
        fwrite($myfile, date('Y-m-d H:i:s',time()) . " response=##" . $result . "##\r\n");
        fclose($myfile);
        return $result;
    }
    */
    
    public function request($param)
    {
        date_default_timezone_set(PRC);
        self::info("request=##" . $param . "##" );
        self::info("client ip=##" . self::get_real_ip() . "##" );
        $result = self::buildSoapXml($param);
        self::info("response=##" . $result . "##" );
        return $result;
    }
    
    public function info($param) {
        //$fileDir = 'K:/';
        $fileDir = '/alidata/www/ddmg_pay/log/';
        date_default_timezone_set(PRC);
        $logFileName = $fileDir . date('Y-m-d') . '.log';
        file_put_contents($logFileName, date('Y-m-d H:i:s',time()) . " " .$param . "\r\n", FILE_APPEND);
    }
    
    public function buildSoapXml($param="") {
        $body = "<Body><Response><IS_SUCCESS>通知成功</IS_SUCCESS></Response></Body>";
        $requestIp = "<RequestIp>" . self::get_real_ip() ."</RequestIp>";
        $seqno = "<SEQNO></SEQNO>";
        if ( preg_match('/<ExternalReference>(.*)<\/ExternalReference>/', $param, $rs) ) {
            $seqno = "<SEQNO>" . $rs[1] . "</SEQNO>";
        }
        $headerResponse = "<Response><ReturnCode>00000000</ReturnCode><ReturnMessage></ReturnMessage></Response>";
        $addXml = $requestIp . $seqno . $headerResponse ;
        $result = preg_replace("/<\/Header>/is", $addXml . "</Header>", $param);
        $result = preg_replace("/<Body>(.*)<\/Body>/is", $body , $result);
        return $result;
    }
 
    public function get_real_ip() {
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
    
}
