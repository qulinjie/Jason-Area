<?php

require_once dirname(__FILE__).'/init.php';

exit;
//收款短信通知
doit::singleton('BcsTradeController')->sendSmsCodeForCollection('62250806009', '大汉电子商务大汉电子商务大汉电子商务', '18', '361604100018170055076');
exit;

//收款单同步erp
$MCH_TRANS_NO = '999701600002'; //流水号
doit::singleton('BcsTradeController')->erp_syncBillsOfCollection($MCH_TRANS_NO);
exit;

$url = 'http://127.0.0.1/ddmg_pay/api/bcsCustomer/getInfo';
$data = array();
$data['loginkey'] = '0f62cd30-0ec6-4c3a-96d3-a5d90169fb04,y9/SvRBLRbqtj8jjANdGcBvRL8CZZVSPfMRAwWBjlk8gfcMwB4W95hgo4BCl9k8Um7jE6sTpSpw/GGO/wCLcGqtC9LZCiAMaVybij9Xx6FD+rHaxFva2N+3cRZZRGn8g+QNtDps1DpQbrhkld6BrHtOIW31WYP8RtzSCYiPUSTE5Why1FfDRDV341qKOFDn+EcOgL2fBBn+wGRR+MIaRdA==';
//$data['loginkey'] = '123';
echo curl::postRequest($url, $data);
exit;

$url = 'http://127.0.0.1/ddmg_pay/bcsCustomer/getInfo?loginkey=1111';
echo curl::getRequest($url);
exit;

//erp验证码校验
$mobile = '13367310112'; //手机号
$codetype = 11;
$code = '42987';
doit::singleton('SmsController')->checkSmsVerificationCode($mobile, $code);
exit;


//更新指定虚拟账户的流水
$virtualAcctNo = '62250806009';//虚拟账户
doit::singleton('BcsTradeController')->spd_loadAccountTradeList_exec($virtualAcctNo);
exit;

//付款单同步erp
doit::singleton('TradeRecordController')->erp_syncBillsOfPayment(601);
exit;


//付款申请单提交erp接口是否可以审批通过
doit::singleton('TradeRecordController')->erp_auditOneTradRecord(593);
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