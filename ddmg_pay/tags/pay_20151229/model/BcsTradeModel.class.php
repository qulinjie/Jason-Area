<?php

class BcsTradeModel extends CurlModel {
   
    // 交易状态 1-成功 2-失败 3-未知
    public static $_status_success = 1;
    public static $_status_failed = 2;
    public static $_status_unknown = 3;
    
    // 功能号 1-部分付款；2-完结付款3：违约付款（功能号为2、3时，该笔订单不允许再发起资金冻结、付款交易）
    public static $_FUNC_CODE_FINISH = 2;
    // 币别 目前只支持：01-人民币
    public static $_CURR_COD_RMB = '01';
    // 使用票据数，没有使用票据填“0”
    public static $_TICKET_NUM_0 = 0;
    // 买方佣金金额
    public static $_SVC_AMT_0 = 0;
    // 卖方佣金金额
    public static $_BVC_AMT_0 = 0;
    
    public static $_comment_build = '创建';
    public static $_comment_success = '成功';
    
    // 导出数据 1-当前页 2-全部
    public static $_export_type_page = 1;
    public static $_export_type_all = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('bcsTrade/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('bcsTrade/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('bcsTrade/update', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('bcsTrade/getInfo', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('bcsTrade/create', $params);
    }
    
}