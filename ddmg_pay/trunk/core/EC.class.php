<?php

define('EC_OK', 0);
define('EC_OK_ERP', 1);
define('EC_DB_CNT', 1);
define('EC_OTH_TKN', 2);
define('EC_NOT_LOGIN', 3);

define('EC_MTD_NON', 4);
define('EC_MOD_NON', 5);
define('EC_CTR_NON', 6);
define('EC_LIB_NON', 7);
define('EC_CNF_NON', 8);
define('EC_CNF_ERR', 9);
define('EC_VIW_NON', 10);
define('EC_MAL_NOT_VRF', 11);
define('EC_FLE_NON', 12);
define('EC_JSN_BAD', 13);
define('EC_PAR_BAD', 14);
define('EC_PRD_ACT', 15);

define('EC_OTH', 99);


define('EC_GEN_CDE', 101);
define('EC_SND_CDE', 102);
define('EC_SND_AGA', 103);
define('EC_NOT_VFY', 104);
define('EC_VFY_EPR', 105);
define('EC_USR_EST', 106);
define('EC_PWD_DEC', 107);
define('EC_ARD_LGN', 108);
define('EC_USR_ADD', 109);
define('EC_USR_NON', 110);
define('EC_PWD_WRN', 111);
define('EC_CHK_OUT', 112);
define('EC_PWD_EMP', 113);
define('EC_PWD_SAM', 114);
define('EC_PWD_UPD', 115);
define('EC_DAT_NON', 116);
define('EC_TEL_NON', 117);
define('EC_OPE_FAI', 118);
define('EC_DEL_FAI', 119);
define('EC_UPD_FAI', 120);
define('EC_ADD_FAI', 121);
define('EC_VER_NCH', 122);
define('EC_SIG_ARD', 123);
define('EC_CERT_ERR',124);

define('EC_RED_EXP', 201);
define('EC_RED_EMP', 202);
define('EC_PAR_ERR', 203);
define('EC_REC_EST', 204);
define('EC_ADD_REC', 205);
define('EC_UPD_REC', 206);
define('EC_LOGIN_PAR_REC', 207);
define('EC_USE_UNA', 220);

define('EC_CODE_ERR', 301);
define('EC_TOKEN_ERR',302);
define('EC_TOKEN_EXP',303);
define('EC_PINCODE_ERR',304);

define('EC_UPL_FILE_NON',401);
define('EC_UPL_FILE_TYPE_ERR',402);

define('EC_BCS_TRADE_REPE',501);
define('EC_SIT_NO_NON',601);

define('EC_ERPE_FAI',701);

define('EC_NOT_SIGN','801');
define('EC_ARY_CANCEL','802');
define('EC_BLE_LESS','803');

define('EC_ACCOUNT_EST',901);
define('EC_COMPANY_EST',902);
define('EC_CERT_BEEN' , 903);

define('EC_TRADE_TF_NO_AS',1001);
define('EC_TRADE_TF_OS_ERR', 1002);
define('EC_TRADE_TF_OS_ERR_2', 1003);
define('EC_TRADE_TF_OS_ERR_3' , 1004);

class EC extends Base {

	public static $_errMsg = array(
			
			EC_OK			=>	'success',
	        EC_OK_ERP	    =>	'success',
	        EC_ERPE_FAI		=>	'与ERP系统通讯失败',
			EC_DB_CNT		=>	'connect to database failed',
			EC_OTH_TKN		=>	'token error, please retry later',
			EC_NOT_LOGIN	=>	'not login',
			EC_MTD_NON		=>	'method does not exists',
			EC_MOD_NON		=>	'mode does not exists',
			EC_CTR_NON		=>	'controller does not exists',
			EC_LIB_NON		=>	'library does not exists',
			EC_CNF_NON		=>	'配置文件错误',
			EC_CNF_ERR		=>	'configuration invalid',
			EC_MAL_NOT_VRF	=>	'the email is not verified',
			EC_OTH			=>	'other error',
			
			EC_FLE_NON		=>	'can not find corresponding file',
			EC_JSN_BAD		=>	'input is not json style',
			EC_PAR_BAD		=>	'input parameter error',
			EC_PRD_ACT		=>	'the same operation is in process',
			
			
			EC_GEN_CDE		=>	'genrate sms code fail',
			EC_SND_CDE		=>	'send sms code fail',
			EC_SND_AGA		=>	'do not send sms code too often',
			EC_CHK_OUT      =>  'sms code expired',
			EC_NOT_VFY		=>	'the telphone number is not verified',
			EC_VFY_EPR		=>	'短信验证码过期',
			EC_USR_EST		=>	'手机号码已被注册',
			EC_PWD_DEC		=>	'密码解密失败',
			EC_ARD_LGN		=>	'a user login already, please logout before login another user',
			EC_USR_ADD		=>	'add user information fail',
			EC_USR_NON		=>	'用户不存在',
			EC_PWD_WRN		=>	'密码错误',
			EC_PWD_EMP		=>	'密码为空',
			EC_PWD_SAM		=>	'same password',
			EC_PWD_UPD		=>	'密码重置失败',
			EC_DAT_NON		=>	'data not exists',
			EC_TEL_NON		=>	'dail failed',
			EC_OPE_FAI		=>	'operation failure',
			EC_DEL_FAI		=>	'delete row failure',
			EC_UPD_FAI		=>	'update row failure',
			EC_ADD_FAI		=>	'insert row failure',
			EC_VER_NCH		=>	'this is the highest versions',
			EC_SIG_ARD		=>	'you haved sign today,can not sign again',
	    
	        EC_RED_EXP      =>  '当前记录，状态异常，请刷新页面', 
	        EC_RED_EMP      =>  '当前记录异常，请刷新页面',
	        EC_PAR_ERR      =>  '提交的参数错误',
    	    EC_REC_EST		=>	'记录已存在',
    	    EC_ADD_REC		=>	'添加记录错误',
    	    EC_UPD_REC		=>	'修改记录错误',
	    
	        EC_CODE_ERR      =>  '授权码验证失败',
			EC_TOKEN_ERR     =>  '禁止非法访问',
			EC_TOKEN_EXP     =>  '由于您长时间未活动，请刷新页面',
			EC_PINCODE_ERR   =>  '验证码输入有误',

			EC_UPL_FILE_NON       => '上传文件不存在',
			EC_UPL_FILE_TYPE_ERR  => '上传文件类型错误',
			EC_LOGIN_PAR_REC	=>	'用户名或密码错误',

			EC_USE_UNA		 =>	'用户被禁用',
			EC_CERT_ERR      => '证书验证失败',
	    
	        EC_BCS_TRADE_REPE      => '存在成功或未明的交易记录',
			EC_SIT_NO_NON     => '席位号不存在',

			EC_NOT_SIGN       => '未签约',
			EC_ARY_CANCEL     => '已注销',
			EC_BLE_LESS       => '余额不足',
	    
	       EC_ACCOUNT_EST     => '帐号已存在',
	       EC_COMPANY_EST     => '公司已存在',
	       EC_CERT_BEEN        => '证件已被占用',
			
			EC_TRADE_TF_NO_AS      => '审批未通过',
			EC_TRADE_TF_OS_ERR     => '订单状态异常',
			EC_TRADE_TF_OS_ERR_2   => '订单已付款',
			EC_TRADE_TF_OS_ERR_3   => '订单已拒付',
	    
	);
	public static function load(){
		return true;
	}
	public static function fail($errno, $unlock = true){
		$response_data = array(
				'caller' => doit::$caller,
				'callee' => doit::$callee,
				'timestamp' => time(),
				'eventid'	=>	doit::$eventid,
				'code' => $errno,
				'msg' => self::$_errMsg[$errno]
		);
		$response = json_encode($response_data);
		
		doit::$res = $response_data;
		doit::$res_str = $response;
		header('Content-type: application/json');
		echo $response;
		//if($unlock)$GLOBALS['processlock_obj']->unlock();
		exit(0);
	}
	
	public static function success($errno, $data = array(), $unlock = true){
	    if( EC_NOT_LOGIN == $errno && $_SERVER['REQUEST_METHOD']!="POST") {
	        $view = View::getInstance();
	        $view->render('index', array( 'code' => $errno, 'msg' => self::$_errMsg[$errno] ));
	        exit(0);
	    }
		$response_data = array(
				'caller' => doit::$caller,
				'callee' => doit::$callee,
				'timestamp' => time(),
				'eventid'	=>	doit::$eventid,
				'code'	=> $errno,
				'msg'	=> self::$_errMsg[$errno],
		);
		if(! empty($data)){
			$response_data['data'] = $data;
		}
		$response = json_encode($response_data);
		
		doit::$res = $response_data;
		doit::$res_str = $response;
		header('Content-type: application/json');
		echo $response;
		//if($unlock)$GLOBALS['processlock_obj']->unlock();
		exit(0);
	}

	public static function fail_page($code, $return = false)
	{
		return self::error_page($code, self::$_errMsg[$code], $return);
	}
	
	public static function page_not_found($return = false)
	{
		return self::error_page(404, '页面没有找到', $return);
	}

	public static function error_page($code, $message, $return = false)
	{
		/*if($code < 100){
			header("Location: " . Router::getBaseUrl() . "index.php/view/error/" . $code);
			exit(0);
		}*/
		$view = View::getInstance();
		if($return)
			return $view->render('error/message', array('code' => $code, 'message' => $message), true);
		else $view->render('error/message', array('code' => $code, 'message' => $message));
		exit(0);
	}
	
}
