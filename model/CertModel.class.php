<?php

class CertModel extends CurlModel {
    
    public function create($params = array()){
        return self::sendRequest('cert/create',$params);
    }
    
    public function update($params = array()){
        return self::sendRequest('cert/update',$params);
    }
    
    public function delete($params = array()){
        return self::sendRequest('cert/delete',$params);
    }
    
    public function getList($params = array()){
        return self::sendRequest('cert/getList', $params);
    }
}