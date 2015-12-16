<?php

class BcsMarketModel extends CurlModel {
   
    // 导出数据 1-当前页 2-全部
    public static $_export_type_page = 1;
    public static $_export_type_all = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('bcsMarket/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('bcsMarket/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('bcsMarket/update', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('bcsMarket/getInfo', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('bcsMarket/create', $params);
    }
    
}