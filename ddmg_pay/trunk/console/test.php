<?php

require_once dirname(__FILE__).'/init.php';

//付款申请单提交erp接口是否可以审批通过
doit::singleton('TradeRecordController')->erp_auditOneTradRecord(593);
exit;

//付款单同步erp
doit::singleton('TradeRecordController')->erp_syncBillsOfPayment(593); 
exit;

//收款单同步erp
$MCH_TRANS_NO = '999701650004'; //流水号
doit::singleton('BcsTradeController')->erp_syncBillsOfCollection($MCH_TRANS_NO);
exit;

//获取erp订单信息
$fphm = 'LDRK002-00000002';
doit::singleton('TradeRecordController')->erp_getOrderBuyInfo($fphm);
exit;

//print_r(TradeRecordController::getBackhostStatus());

//收款短信通知
doit::singleton('BcsTradeController')->sendSmsCodeForCollection('62250806009', '大汉电子商务', '18');
exit;