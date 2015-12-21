<?php

class BcsTransferModel extends CurlModel {
   
    // 交易状态 1-成功 2-失败 3-未知
    public static $_status_success = 1;
    public static $_status_failed = 2;
    public static $_status_unknown = 3;
    
    // 币别 目前只支持：01-人民币
    public static $_CURR_COD_RMB = '01';
    
    // 客户出入金类型/方向   1-出金 2-入金
    public static $_transfer_type_out = 1;
    public static $_transfer_type_in = 2;
    
    public static $_comment_build = '创建';
    public static $_comment_success = '成功';
    
    // 导出数据 1-当前页 2-全部
    public static $_export_type_page = 1;
    public static $_export_type_all = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('bcsTransfer/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('bcsTransfer/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('bcsTransfer/update', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('bcsTransfer/getInfo', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('bcsTransfer/create', $params);
    }
    
}