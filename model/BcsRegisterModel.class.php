<?php

class BcsRegisterModel extends CurlModel {
   
    // 导出数据 1-当前页 2-全部
    public static $_export_type_page = 1;
    public static $_export_type_all = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('bcsRegister/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('bcsRegister/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('bcsRegister/update', $params);
    }
    
    public function getList($params = array()){
        return self::sendRequest('bcsRegister/getList', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('bcsRegister/create', $params);
    }
    
    public function createByJava($params = array()){
//         return self::sendRequestByJava('payBcsWs/wsDemo', $params); // for test
        return self::sendRequestByJava('payBcsWs/ws ', $params);
    }
}