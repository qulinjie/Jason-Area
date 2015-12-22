<?php

class BcsChildAccountModel extends CurlModel {
   
    // 导出数据 1-当前页 2-全部
    public static $_export_type_page = 1;
    public static $_export_type_all = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function create($params = array()){
        return self::sendRequest('bcsChildAccount/create', $params);
    }
    
    public function delete($params = array()){
        return self::sendRequest('bcsChildAccount/delete', $params);
    }
    
}