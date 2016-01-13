<?php

class CertModel extends CurlModel {

    
    public function get($params = array()){
        return self::sendRequest('Cert/get', $params);
    }
}