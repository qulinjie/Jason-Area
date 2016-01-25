<?php

class TradeRecordItemModel extends CurlModel {
   
    // 是否删除 1-正常 2-已删除
    public static $_is_delete_false = 1;
    public static $_is_delete_true = 2;
    
    // 导出数据 1-当前页 2-全部
    public static $_export_type_page = 1;
    public static $_export_type_all = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('tradeRecordItem/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('tradeRecordItem/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('tradeRecordItem/update', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('tradeRecordItem/getInfo', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('tradeRecordItem/create', $params);
    }
    
    public function registerNetToServer($params = array()){
        return $this->sendRequestServer('order/paybackup',$params);
    }
    
}
