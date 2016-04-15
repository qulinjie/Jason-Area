<?php

class TradeRecordModel extends CurlModel {
   
    // 是否删除 1-正常 2-已删除
    public static $_is_delete_false = 1;
    public static $_is_delete_true = 2;
    
    // 订单状态 1-待付款 2-已付款 3-拒付
    public static $_status_waiting = 1;
    public static $_status_paid = 2;
    public static $_status_refuse = 3;
    
    // 发货状态 1-未发货 2-已发货
    public static $_send_status_n = 1;
    public static $_send_status_y = 2;
    
    // 实提登记状态 1-未登记 2-已登记
    public static $_check_status_n = 1;
    public static $_check_status_y = 2;
    
    
    // 导出数据 1-当前页 2-全部
    public static $_export_type_page = 1;
    public static $_export_type_all = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('tradeRecord/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('tradeRecord/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('tradeRecord/update', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('tradeRecord/getInfo', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('tradeRecord/create', $params);
    }
    public function create_add($params = array()){
        return self::sendRequest('tradeRecord/create_add', $params);
    }
    
    public function pay($params = array()){
        return self::sendRequest('tradeRecord/pay', $params);
    }
    
    //server update order_status
    public function orderStatusToServer($params = array()) {
        return self::sendRequestServer('order/paystatus',$params);
    }
    
    public function getNextId($params = array()){
        return self::sendRequest('tradeRecord/getNextId', $params);
    }
    
    public function erp_getOrderBuyList($params = array()){
        return self::sendRequestErp('api/pub/OrderService/PostOrder_BuyOrderList/',$params);
    }
    
    public function erp_getOrderBuyInfo($params = array()){
        return self::sendRequestErp('api/pub/OrderService/PostOrder_BuyOrderInfo/',$params);
    }
    
    public function erp_getSellOrderList($params = array()){
    	return self::sendRequestErp('api/pub/OrderService/PostOrder_SellOrderList/',$params);
    }
    
    public function erp_getSellOrderInfo($params = array()){
    	return self::sendRequestErp('api/pub/OrderService/PostOrder_SellOrderInfo/',$params);
    }
    
    public function erp_getOrgNameInfo($params = array()){
        return self::sendRequestErp('api/pub/ErpService/PostERP_wldw/',$params);
    }  

    public function erp_syncBillsOfPayment($params = array()){
    	return self::sendRequestErp('api/pub/FinanceService/PostCW_FKDCreate/',$params);
    }
        
    public function auditOneTradRecord($params = array()){
    	return self::sendRequest('tradeRecord/auditOneTradRecord/',$params);
    }
    
    public function erp_auditOneTradRecord($params = array()){
    	return self::sendRequestErp('api/pub/FinanceService/PostCW_FKDAudit/',$params);
    }    
   
}
