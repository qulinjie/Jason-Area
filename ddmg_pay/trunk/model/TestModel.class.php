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
    
}