<?php
class SmsModel extends CurlModel {

    //短信发送erp
    public function erp_sendSmsCode($params = array()){
    	return self::sendRequestErp('/api/pub/KKunService/PostKKun_sendsmscode/',$params);
    }
    
    //校验验证码
    public function erp_checkSmsCode($params = array()){
    	return self::sendRequestErp('/api/pub/KKunService/PostKKun_checksmscode/',$params);
    }
    
}