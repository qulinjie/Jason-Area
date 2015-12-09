<?php

class TradeRecordModel extends CurlModel {
   
    // 是否删除 1-正常 2-已删除
    public static $_is_delete_false = 1;
    public static $_is_delete_true = 2;
    
    // 订单交易状态 1-待付款 2-已付款 3-拒付
    public static $_status_waiting = 1;
    public static $_status_paid = 2;
    public static $_status_refuse = 3;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function create($params = array()){
        return self::sendRequest('tradeRecord/create', $params);
    }
    
}
