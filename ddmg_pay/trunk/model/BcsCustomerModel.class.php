<?php

class BcsCustomerModel extends CurlModel {
   
    // 客户状态 1-已注册；2-已签约；3-已注销
    public static $_status_register = 1;
    public static $_status_signed = 2;
    public static $_status_cancelled = 3;
    
    // 导出数据 1-当前页 2-全部
    public static $_export_type_page = 1;
    public static $_export_type_all = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('bcsCustomer/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('bcsCustomer/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('bcsCustomer/update', $params);
    }
    
    public function updateBild($params = array()){
        return self::sendRequest('bcsCustomer/updateBild', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('bcsCustomer/getInfo', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('bcsCustomer/create', $params);
    }
    
    public function getList($params = array()){
        return self::sendRequest('bcsCustomer/getList', $params);
    }
    
}