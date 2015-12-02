<?php

class ServicesController extends Controller {
    
    public function handle($params = array()) {
        if(empty($params)){
            Log::error ('AdminController . params is empty . ');
            EC::fail (EC_MTD_NON);
        }else {
            switch ($params[0]){
                case 'login':
                    //$this->login();
                    break;
                default :
                    Log::error ('page not found . ' . $params[0]);
                    EC::fail (EC_MTD_NON);
                    break;
            }
        }
    }
    
    public function request($param) {
        date_default_timezone_set(PRC);
        self::info("request=##" . $param . "##" );
        self::info("client ip=##" . self::get_real_ip() . "##" );
        $result = self::buildSoapXml($param);
        self::info("response=##" . $result . "##" );
        return $result;
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
    
    public function info($param) {
        Log::notice($param);
    }
    
}