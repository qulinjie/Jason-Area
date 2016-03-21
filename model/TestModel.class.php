<?php

class TestModel extends CurlModel {
    
    public function xmlToArray( $xml )
    {
        return json_decode( json_encode( simplexml_load_string( $xml ) ), true );
    }
    
    public function gbk2utf8($str){
        $charset = mb_detect_encoding($str,array('UTF-8','GBK','GB2312'));
        $charset = strtolower($charset);
        if('cp936' == $charset){
            $charset='GBK';
        }
        if("utf-8" != $charset){
            $str = iconv($charset,"UTF-8//IGNORE",$str);
        }
        return $str;
    }
    
    public function testSpdSign1($params = array()){
//         Log::notice("\r\n\r\n=============testSpdSign1===============\r\n\r\n  params = ##" . $params . "## \r\n\r\n");
//         $params = iconv('UTF-8', 'GB2312', $params);
//         Log::notice("\r\n\r\n=============testSpdSign1===============\r\n\r\n  params(iconv) = ##" . $params . "## \r\n\r\n");
//         exit;
        $data = self::sendRequestSpdSign('', $params);
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        return $data;
    }
    
    public function testSpdSend1($params = array()){
        $data = self::sendRequestSpdSend('', $params);
        $data = substr($data,6);
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        return $data;
    }
    
    public function testSpdSign2($params = array()){
        $data = self::sendRequestSpdSign('', $params, 'v');
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        return $data;
    }
    
    
    public function test_sendRequest($interface,$data){
        $base_data = [ 'caller'=>'ddmg_pay', 'callee'=>'ebLlyZDBSGgp', 'eventid'=>rand()%10000, 'timestamp'=>time() ];
        $base_data['data'] = $data;
    
        $data = $this->test_sendRequestServer($interface, $base_data);
        Log::notice("test_sendRequest data ===============================>> response = ##" . json_encode($data) . "##" );
        
        return $data;
    }
    
    
}