<?php

class SmsController extends Controller {

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
    * @param: $codetype 11付款一级审核短信 、12支付密码重置短信
    * @return:
    */
    public static function sendSmsVerificationCode($mobile = NULL, $codetype = NULL){
    	
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
    	$data['codetype'] = $codetype; //11付款一级审核短信 、12支付密码重置短信
    	
    	Log::notice("request-data ============>> data = ##" . json_encode($data) . "##" );
    	$sms_model = self::model('sms');
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
    
    
    /**
     * 收款后发送短信给用户
     * @date: 2016-4-14 下午3:30:52
     * @author: lw
     * @param:  $ACCOUNT_NO 虚拟帐号  $payer 付款单位名称
     * $amount 付款金额
     * $payer_no 付款银行号
     * @return:
     */
    public static function sendSmsCodeForCollection($ACCOUNT_NO, $payer, $amount, $payer_no = NULL){
    	 
    	Log::skdxNotice('sendSmsCodeForCollection . ACCOUNT_NO='. $ACCOUNT_NO .',payer='. $payer .',amount='. $amount . ',payer_no=' . $payer_no);
    	if(empty($ACCOUNT_NO) || empty($payer) || empty($amount)){
    		Log::skdxError('empty args!');
    		//EC::fail(EC_PAR_ERR);
    		return false;
    	}
    	 
    	//查合伙人信息
    	$bcs_params  = array();
    	$bcs_params['ACCOUNT_NO'] = $ACCOUNT_NO;
    	$bcsCustomer_model = self::model('bcsCustomer');
    	$bcs_data = $bcsCustomer_model->getInfo($bcs_params);
    	if(EC_OK != $bcs_data['code'] || !is_array($bcs_data) || !isset($bcs_data['data'])){
    		Log::skdxError("bcsCustomer getInfo failed . ");
    		//EC::fail(EC_USR_NON);
    		return false;
    	}
    	$bcs_data = $bcs_data['data'][0];
    	if(empty($bcs_data)) {
    		Log::skdxError('bcsCustomer getInfo empty !');
    		//EC::fail(EC_RED_EMP);
    		return false;
    	}
    	 
    	//根据user_id查erp接口得到用户信息
    	$user_data = array();
    	$user_model = self::model('user');
    	$user_data = $user_model->erp_getInfo(array('usercode' => $bcs_data['user_id']));
    	if(EC_OK_ERP != $user_data['code']){
    		Log::skdxError('erp_getInfo Fail!' . $user_data['msg']);
    		//EC::fail($user_data['code']);
    		return false;
    	}
    	$user_data = $user_data['data'];
    	if(empty($user_data) || !isset($user_data['mobile']) || empty($user_data['mobile'])){
    		Log::skdxError('mobile is empty!');
    		//EC::fail(EC_DATA_EMPTY_ERR);
    		return false;
    	}
    	$mobile = $user_data['mobile'];
    	 
    	//判断是否为大合伙人,如果不是大合伙人，则查大合伙人电话
    	if(!empty($user_data['fuserid']) && $bcs_data['user_id'] != $user_data['fuserid']){
    		$user_data2 = $user_model->erp_getInfo(array('usercode' => $user_data['fuserid']));
    		if(EC_OK_ERP != $user_data2['code']){
    			Log::skdxError('2 erp_getInfo Fail!' . $user_data2['msg']);
    			//EC::fail($user_data2['code']);
    			return false;
    		}
    		$user_data2 = $user_data2['data'];
    		if(!empty($user_data2) && isset($user_data2['mobile']) && !empty($user_data2['mobile'])){
    			$mobile = $user_data2['mobile'];
    		}
    	}
    	 
    	// 尊敬的客户，【Value1】已提交支付，支付【Value2】为【Value3】，请及时跟进。感谢您的支持【Value4】
    	$data = array();
    	$data['tel'] = $mobile; //'13367310112'电话
    	$data['codetype'] = '10';
    	//$payer_no = empty($payer_no) ? '' : '(账号:'.$payer_no.')';
    	$value1 = $payer;
    	$data['value1'] = $value1 ; //付款公司名称
    	$data['value2'] = '金额';
    	$data['value3'] = $amount.'元';
    	$data['value4'] = '!';    
    
    	Log::skdxNotice("request-data ============>> data = ##" . json_encode($data) . "##" );
    	$sms_model = self::model('sms');
    	$res_data = $sms_model->erp_sendSmsCode($data);
    	Log::skdxNotice("response-data ============>> res_data = ##" . json_encode($res_data) . "##" );
    	 
    	return true;
    	//EC::success(EC_OK, $res_data);
    }
    
    /**
     * 收款后发送短信给用户
     * @date: 2016-4-14 下午3:30:52
     * @author: lw
     * @param:  $ACCOUNT_NO 虚拟帐号  $payer 付款单位名称 
     * $amount 付款金额
     * $payer_no 付款银行号
     * @return:
     */
    public static function sendSmsCodeForCollection2($ACCOUNT_NO, $time, $payer, $amount, $payer_no = NULL){
    
    	Log::skdxNotice('sendSmsCodeForCollection . ACCOUNT_NO='. $ACCOUNT_NO .',time='. $time .',payer='. $payer .',amount='. $amount . ',payer_no=' . $payer_no);
    	if(empty($ACCOUNT_NO) || empty($payer) || empty($amount)){
    		Log::skdxError('empty args!');
    		//EC::fail(EC_PAR_ERR);
    		return false;
    	}
    
    	//查合伙人信息
    	$bcs_params  = array();
    	$bcs_params['ACCOUNT_NO'] = $ACCOUNT_NO;
    	$bcsCustomer_model = self::model('bcsCustomer');
    	$bcs_data = $bcsCustomer_model->getInfo($bcs_params);
    	if(EC_OK != $bcs_data['code'] || !is_array($bcs_data) || !isset($bcs_data['data'])){
    		Log::skdxError("bcsCustomer getInfo failed . ");
    		//EC::fail(EC_USR_NON);
    		return false;
    	}
    	$bcs_data = $bcs_data['data'][0];
    	if(empty($bcs_data)) {
    		Log::skdxError('bcsCustomer getInfo empty !');
    		//EC::fail(EC_RED_EMP);
    		return false;
    	}
    
    	//根据user_id查erp接口得到用户信息
    	$user_data = array();
    	$user_model = self::model('user');
    	$user_data = $user_model->erp_getInfo(array('usercode' => $bcs_data['user_id']));
    	if(EC_OK_ERP != $user_data['code']){
    		Log::skdxError('erp_getInfo Fail!' . $user_data['msg']);
    		//EC::fail($user_data['code']);
    		return false;
    	}
    	$user_data = $user_data['data'];
    	if(empty($user_data) || !isset($user_data['mobile']) || empty($user_data['mobile'])){
    		Log::skdxError('mobile is empty!');
    		//EC::fail(EC_DATA_EMPTY_ERR);
    		return false;
    	}
    	$mobile = $user_data['mobile'];
    
    	//判断是否为大合伙人,如果不是大合伙人，则查大合伙人电话
    	if(!empty($user_data['fuserid']) && $bcs_data['user_id'] != $user_data['fuserid']){
    		$user_data2 = $user_model->erp_getInfo(array('usercode' => $user_data['fuserid']));
    		if(EC_OK_ERP != $user_data2['code']){
    			Log::skdxError('2 erp_getInfo Fail!' . $user_data2['msg']);
    			//EC::fail($user_data2['code']);
    			return false;
    		}
    		$user_data2 = $user_data2['data'];
    		if(!empty($user_data2) && isset($user_data2['mobile']) && !empty($user_data2['mobile'])){
    			$mobile = $user_data2['mobile'];
    		}
    	}
    	
    	$value1 = strval($ACCOUNT_NO);
    	$value1 = substr_replace($value1, '***', 0, strlen($value1)-3);
    	if(is_int($time)){
    		$time = date("Y年m月d日 H:i", $time);
    	}else if(empty($time)){
    		$time = date("Y年m月d日 H:i", time());
    	}
    
    	// 账户【Value1】于【Value2】收到【Value3】汇款：【Value4】。
    	// 账户*999于04月16日11:30收到XXX公司汇款：3495.00元。【大大买钢】    	
    	//$payer_no = empty($payer_no) ? '' : '(账号:'.$payer_no.')';
    	$data = array();
    	$data['tel'] = $mobile; //'13367310112'电话
    	$data['codetype'] = '';    	    	
    	$data['value1'] = $value1; 
    	$data['value2'] = $time;
    	$data['value3'] = $payer;
    	$data['value4'] = $amount.'元';
    
    	Log::skdxNotice("request-data ============>> data = ##" . json_encode($data) . "##" );
    	$sms_model = self::model('sms');
    	$res_data = $sms_model->erp_sendSmsCode($data);
    	Log::skdxNotice("response-data ============>> res_data = ##" . json_encode($res_data) . "##" );
    
    	return true;
    	//EC::success(EC_OK, $res_data);
    }

    /**
     * 付款后发送短信给申请人
     * @date: 2016-4-19 
     * @author: lw
     * @param:
     * ACCOUNT_NO 申请人的虚拟帐号
     * time 付款时间
     * payee 收款公司名
     * amount 付款金额
     * payee_no 收款银行号
     * @return:
     * 
     * 帐户【Value1】于【Value2】向【Value3】成功付款：【Value4】
     * 账户*999于04月16日11:30向XXX公司成功付款：3495.00元。【大大买钢】
     */
    public static function sendSmsCodeForPayment($ACCOUNT_NO, $time, $payee, $amount, $payee_no = NULL){
    
    	Log::fkdxNotice('sendSmsCodeForPayment . ACCOUNT_NO='. $ACCOUNT_NO .',time='. $time .',payee='. $payee .',amount='. $amount . ',payee_no=' . $payee_no);
    	if(empty($ACCOUNT_NO) || empty($payee) || empty($amount)){
    		Log::fkdxError('empty args!');
    		//EC::fail(EC_PAR_ERR);
    		return false;
    	}
    
    	//查合伙人信息
    	$bcs_params  = array();
    	$bcs_params['ACCOUNT_NO'] = $ACCOUNT_NO;
    	$bcsCustomer_model = self::model('bcsCustomer');
    	$bcs_data = $bcsCustomer_model->getInfo($bcs_params);
    	if(EC_OK != $bcs_data['code'] || !is_array($bcs_data) || !isset($bcs_data['data'])){
    		Log::fkdxError("bcsCustomer getInfo failed . ");
    		//EC::fail(EC_USR_NON);
    		return false;
    	}
    	$bcs_data = $bcs_data['data'][0];
    	if(empty($bcs_data)) {
    		Log::fkdxError('bcsCustomer getInfo empty !');
    		//EC::fail(EC_RED_EMP);
    		return false;
    	}
    
    	//根据user_id查erp接口得到用户信息
    	$user_data = array();
    	$user_model = self::model('user');
    	$user_data = $user_model->erp_getInfo(array('usercode' => $bcs_data['user_id']));
    	if(EC_OK_ERP != $user_data['code']){
    		Log::fkdxError('erp_getInfo Fail!' . $user_data['msg']);
    		//EC::fail($user_data['code']);
    		return false;
    	}
    	$user_data = $user_data['data'];
    	if(empty($user_data) || !isset($user_data['mobile']) || empty($user_data['mobile'])){
    		Log::fkdxError('mobile is empty!');
    		//EC::fail(EC_DATA_EMPTY_ERR);
    		return false;
    	}
    	$mobile = $user_data['mobile'];  

    	$value1 = strval($ACCOUNT_NO);
    	$value1 = substr_replace($value1, '***', 0, strlen($value1)-3);
    	if(is_int($time)){
    		$time = date("Y年m月d日 H:i", $time);
    	}else if(empty($time)){
    		$time = date("Y年m月d日 H:i", time());
    	}
    
    	//帐户【Value1】于【Value2】向【Value3】成功付款：【Value4】
    	$data = array();
    	$data['tel'] = '13367310112'; //$mobile; //'13367310112'电话
    	$data['codetype'] = '';
    	//$payee_no = empty($payee_no) ? '' : '(账号:'.$payee_no.')';
    	$value3 = $payee;
    	$data['value1'] = $value1; 
    	$data['value2'] = $time;
    	$data['value3'] = $value3;
    	$data['value4'] = $amount . '元';
        
    	Log::fkdxNotice("request-data ============>> data = ##" . json_encode($data) . "##" );
    	$sms_model = self::model('sms');
    	$res_data = $sms_model->erp_sendSmsCode($data);
    	Log::fkdxNotice("response-data ============>> res_data = ##" . json_encode($res_data) . "##" );
    
    	return true;
    	//EC::success(EC_OK, $res_data);
    }
    
    /**
     * 一级审核后发送短信给二级审核人
     * @date: 2016-4-19
     * @author: lw
     * @param:
     * $user_id 二级审核人的user_id
     * @return:
     *  您好【Value1】，您有一笔【Value2】，请尽快处理。
     *  您好，您有一笔付款申请待审核，请尽快处理。【大大买钢】   
     */
    public static function sendSmsToSecondAuditUser($user_id){
    
    	Log::notice('sendSmsToSecondAuditUser . user_id='. $user_id);
    	if(empty($user_id)){
    		Log::error('empty args!');
    		//EC::fail(EC_PAR_ERR);
    		return false;
    	}
        
    	//根据user_id查erp接口得到用户信息
    	$user_data = array();
    	$user_model = self::model('user');
    	$user_data = $user_model->erp_getInfo(array('usercode' => $user_id));
    	if(EC_OK_ERP != $user_data['code']){
    		Log::error('erp_getInfo Fail!' . $user_data['msg']);
    		//EC::fail($user_data['code']);
    		return false;
    	}
    	$user_data = $user_data['data'];
    	if(empty($user_data) || !isset($user_data['mobile']) || empty($user_data['mobile'])){
    		Log::error('mobile is empty!');
    		//EC::fail(EC_DATA_EMPTY_ERR);
    		return false;
    	}
    	$mobile = $user_data['mobile']; 
    
    	//  您好【Value1】，您有一笔【Value2】，请尽快处理。
        //  您好，您有一笔付款申请待审核，请尽快处理。【大大买钢】   
    	$data = array();
    	$data['tel'] = '13367310112'; //$mobile; //'13367310112'电话
    	$data['codetype'] = '';    	
    	$data['value1'] = '';
    	$data['value2'] = '付款申请待审核';    	
    
    	Log::notice("request-data ============>> data = ##" . json_encode($data) . "##" );
    	$sms_model = self::model('sms');
    	$res_data = $sms_model->erp_sendSmsCode($data);
    	Log::notice("response-data ============>> res_data = ##" . json_encode($res_data) . "##" );
    
    	return true;
    	//EC::success(EC_OK, $res_data);
    }
    
}