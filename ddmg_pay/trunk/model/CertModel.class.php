<?php

class CertModel extends CurlModel {

    
    public function getInfo($params = array()){
        return self::sendRequest('Cert/getInfo', $params);
    }
}