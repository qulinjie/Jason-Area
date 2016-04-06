<?php

class SmsController extends BaseController {

    public function handle($params = array()) {
        Log::notice('SmsController  ==== >>> params=' . json_encode($params));
        if (empty($params)) {
            Log::error('Controller . params is empty . ');
            EC::fail(EC_MTD_NON);
        } else {
            switch ($params[0]) {            	
                case 'sendSmsVerificationCode':
                	$this->sendSmsVerificationCode();
                	break;                	
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }
     
    /**
    * 发送短信验证码，格式：尊敬的客户，您的验证码为：【Value1】，如非本人操作，请忽略本短信
    * @date: 2016-4-6 下午2:52:31
    * @author: lw
    * @param: $mobile 手机号
    * @param: $codetype 11付款审核短信 、12付款申请短信
    * @return:
    */
    public function sendSmsVerificationCode($mobile = NULL, $codetype = NULL){
    	
    	$mobile = ($mobile == NULL) ? Request::post('mobile') : $mobile;
    	$codetype = ($codetype == NULL) ? intval(Request::post('codetype')) : intval($codetype);
    	Log::notice("sendSmsVerificationCode ===>> mobile=" . $mobile . "codetype=" .$codetype);
    	
    	if(empty($mobile) || empty($codetype)){
    		Log::error('sendSmsVerificationCode params error!');
    		EC::fail(5000, EC::$_errMsg[EC_PAR_ERR]);
    	}
    	
    	//尊敬的客户，您的验证码为：【Value1】，如非本人操作，请忽略本短信
    	$data = array();
    	$data['tel'] = $mobile; //$mobile '13367310112'电话
    	$data['codetype'] = $codetype; //11付款审核短信 、12付款申请短信
    	
    	Log::notice("request-data ============>> data = ##" . json_encode($data) . "##" );
    	$sms_model = $this->model('sms');
    	$res_data = $sms_model->erp_sendSmsCode($data);
    	Log::notice("response-data ============>> res_data = ##" . json_encode($res_data) . "##" );

    	if(EC_OK_ERP != $res_data['code']){
    		Log::error('sendSmsVerificationCode Fail!'.$res_data['msg']);
    		EC::fail(5000, $res_data['msg']);    		
    	}
    	
    	//return true;
    	EC::success(EC_OK, $res_data);    	
    }
        
    /**
    * 检测发送的验证码是否正确
    * @date: 2016-4-6 下午2:54:06
    * @author: lw
    * @param: variable
    * @return:
    */
    public static function checkSmsVerificationCode($mobile, $codetype, $code){
    	
    	//$vcode = ($vcode == NULL) ? intval(Request::post('vcode')) : intval($vcode);
    	Log::notice("checkSmsVerificationCode ===>> mobile=". $mobile ." codetype=" .$codetype ." code=" .$code );
    	 
    	if(empty($mobile) || empty($codetype) || empty($code)){
    		Log::error('checkSmsVerificationCode params error!');
    		EC::fail(5000, EC::$_errMsg[EC_PAR_ERR]);
    		//return false;
    	}
    	
    	$data = array();
    	$data['tel'] = $mobile; //$mobile '13367310112'电话    	
    	$data['code'] = $code;
    	$data['codetype'] = $codetype;
    	
    	Log::notice("request-data ============>> data = ##" . json_encode($data) . "##" );
    	$sms_model = self::model('sms');
    	$res_data = $sms_model->erp_checkSmsCode($data);
    	Log::notice("response-data ============>> res_data = ##" . json_encode($res_data) . "##" );

    	//Log::write(var_export($res_data, true), 'debug', 'debug222-'.date('Y-m-d'));
    	
    	if(EC_OK_ERP != $res_data['code']){
    		Log::error('erp_sendSmsCode Fail!'. $res_data['msg']);
    		EC::fail(5000, $res_data['msg']);
    		//return false;
    	} 
    	
    	//EC::success(EC_OK, $res_data);
    	return true;
    }
    

    
}