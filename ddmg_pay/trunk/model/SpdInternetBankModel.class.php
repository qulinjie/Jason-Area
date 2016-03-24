<?php

class SpdInternetBankModel extends CurlModel {
   
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('spdInternetBank/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('spdInternetBank/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('spdInternetBank/update', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('spdInternetBank/getInfo', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('spdInternetBank/create', $params);
    }
    
    public function delete($params = array()){
        return self::sendRequest('spdInternetBank/delete', $params);
    }

}
