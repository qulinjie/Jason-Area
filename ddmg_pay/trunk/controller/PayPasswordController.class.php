<?php


class PayPasswordController extends BaseController
{
    public function handle($params = array())
    {   
        switch ($params[0]) {
        	case 'set':
        		$this->set();
        		break;
            case 'reset':
                $this->reset();
                break;                
            case 'notice':
                $this->notice();
                break;
            default:
                Log::error('PayPasswordController method not exists ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }
    
    private function set(){
    	
    	if(IS_POST){
	    	if(!$pay_pwd = self::decrypt($this->post('pay_pwd'))){
	    		Log::error('payPassword set params error');
	    		EC::fail(EC_PAR_BAD);
	    	}	    		    	   	
	    	
	    	if(self::checkExist()){
	    		Log::error('payPassword have set');
	    		EC::fail(1, '设置失败：支付密码已存在！');
	    	}
	    	
	    	$current_user_id = UserController::getCurrentUserId();
	    	$pay_pwd_data = array();
	    	$pay_pwd_data['usercode'] = $current_user_id;
	    	$pay_pwd_data['paypass'] = $pay_pwd;
	    	$user_model = $this->model('user');
	    	$res_data = $user_model->erp_payPwdSet($pay_pwd_data);
	    	
	    	
	    	if($res_data['code'] != EC_OK_ERP){
	    		Log::error('erp_payPwdSet error'. $res_data['msg']);
	    		EC::fail($res_data['code'], $res_data['msg']);
	    	}
	    	self::$_isCheckExist = true;
	    	EC::success(EC_OK);
    	}
    	
    	if(self::checkExist()){
    		$this->redirect(Router::getBaseUrl().'payPassword/reset');    		
    	}
    	$password_html = $this->render('payPasswordSet',[],true);
    	$this->render('index',['page_type' => 'payPassword','password_html' => $password_html]);
    }

    private function reset(){
    	
        if(IS_POST){
        	if(!$code = $this->post('code')){
        		Log::error('payPassword reset params error');
        		EC::fail(EC_PAR_BAD);
        	}
            if(!$pay_pwd = self::decrypt($this->post('pay_pwd'))){
                Log::error('payPassword reset params error');
                EC::fail(EC_PAR_BAD);
            }
            
            $mobile = UserController::getUserMobileByUserId(UserController::getCurrentUserId());             
            //检查验证码是否相等
            //SmsController::checkSmsVerificationCode($mobile, 12, $code);

            $current_user_id = UserController::getCurrentUserId();
            $pay_pwd_data = array();
            $pay_pwd_data['usercode'] = $current_user_id;
            $pay_pwd_data['paypass'] = $pay_pwd;
            $pay_pwd_data['usertel'] = $mobile;
            $pay_pwd_data['telcode'] = $code; 
            $user_model = $this->model('user');
            $res_data = $user_model->erp_payPwdReset($pay_pwd_data);
			
            if(EC_OK_ERP != $res_data['code']){
            	Log::error('erp_payPwdReset Fail!'. $res_data['msg']);
            	EC::fail(7000, $res_data['msg']);            	
            }
            
            EC::success(EC_OK);
        }        
        $mobile = UserController::getUserMobileByUserId(UserController::getCurrentUserId());        
        $password_html = $this->render('payPasswordReset',['status' => self::checkExist(), 'mobile' => $mobile],true);
        $this->render('index',['page_type' => 'payPassword','password_html' => $password_html]);
    }

    private function notice()
    {
        $password_html = $this->render('payPasswordNotice',[],true);
        $this->render('index',['page_type' => 'payPassword','password_html' => $password_html]);
    }

    private static $_isCheckExist = NULL;
    public static function checkExist()
    {
    	if(NULL !== self::$_isCheckExist){
    		return self::$_isCheckExist;
    	}
        if(!$loginUser = UserController::getLoginUser()){
            Log::error('PayPassword check not login');
            EC::fail(EC_NOT_LOGIN);
        }
        if($loginUser['paypwd_isexist'] == 1){
        	return self::$_isCheckExist = true;
        }
        return self::$_isCheckExist = false;
    }

    public static function verify($pay_pwd, $is_ec = false)
    {
        //支付密码校验
        if(empty($pay_pwd)){
        	Log::error('verify params error!');
        	if($is_ec){EC::fail(6000, EC::$_errMsg[EC_PAR_ERR]);}
        	return false;
        }
        
        $current_user_id = UserController::getCurrentUserId();
        $pay_pwd_data = array();
        $pay_pwd_data['usercode'] = $current_user_id;
        $pay_pwd_data['paypass'] = $pay_pwd;
        $user_model = $this->model('user');
        $res_data = $user_model->erp_payPwdVerify($pay_pwd_data);        
        if(EC_OK_ERP != $res_data['code']){
        	Log::error('erp_payPwdVerify Fail!'. $res_data['msg']);
        	if($is_ec){ EC::fail(6000, $res_data['msg']);}
        	return false;
        }
		
        return true;
    }

    public static function filter()
    {
        return [
            'token' => ['passwordReset'],
            'login' => ['passwordReset']
        ];
    }
}