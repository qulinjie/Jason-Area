<?php
class AdminController extends Controller {	
	public static $adminSessionKey = 'loginUser'; //admin的session键名

    public function handle($params = array()) {
        if(empty($params)){
            Log::error ('AdminController . params is empty . ');
            EC::fail (EC_MTD_NON);
        }else {
            switch ($params[0]){
                case 'mgr':
                    $this->display('index');
                    break;
                case 'login':
                    $this->login();
                    break;
                case 'loginOut':
                    $this->loginOut();
                    break;
                case 'changePwd':
                    $this->changePwd();
                    break;
                default :
                   Log::error ('page not found . ' . $params[0]);
                   EC::fail (EC_MTD_NON);
                   break;
            }
        }
    }
    
    private static $_loginUser = NULL;
    public static function getLoginUser(){
    	$loginUser = NULL;
    	
    	if(NULL !== self::$_loginUser){
    		return self::$_loginUser;
    	}
    	    	
    	$session = self::instance('session');
    	if($session->is_set(self::$adminSessionKey)){
    		$loginUser = $session->get(self::$adminSessionKey);
    	}
    	
    	if(!$loginUser){    	
	    	try{
	    		$admin_model = self::model('admin');
	    		$data = $admin_model->isLogin();    	
	    		if(empty($data) || EC_OK != $data['code']){
	    			Log::error('isLogin data is empyty or code is err . data=' . json_encode($data) );
	    			$loginUser = [];
	    		}	    	
	    		$loginUser = $data['data'];
	    		if(empty($loginUser)){
	    			Log::error('isLogin . data[loginUser] is null .');
	    			$loginUser = [];
	    		}
	    		self::setLoginSession($loginUser);	    		
	    	} catch (Exception $e) {
	    		Log::error('isLogin . e=' . $e->getMessage());
	    		$loginUser = [];
	    	}
    	}
    	
    	return self::$_loginUser = $loginUser;     	
    }
    
    protected static function setLoginSession($loginUser){
        if(empty($loginUser)){
            Log::error('setLoginSession [loginUser] is empty .');
            return false;
        }
        $session = self::instance('session');
        if(isset($loginUser['password'])) unset( $loginUser['password'] );
        $session->set(self::$adminSessionKey, $loginUser);
        Log::notice('setLoginSession==>>sessionId=' . $session->get_id() . ' ,loginUser=' . json_encode($loginUser) );        
        Log::notice('check setLoginSession . is_set[loginUser]=' . ($session->is_set(self::$adminSessionKey)) );
        Log::notice('check setLoginSession . get[loginUser]=' . json_encode($session->get(self::$adminSessionKey)) );
        return true;
    }
    
    private static $_isAdmin = NULL;
    public static function isAdmin(){
    	
    	if(NULL !== self::$_isAdmin){
    		return self::$_isAdmin;
    	}
    	if(!self::isLogin()){
    		return self::$_isAdmin = false;
    	}
        $loginUser = self::getLoginUser();        
        if(is_array($loginUser) && isset($loginUser['is_admin']) && 'yes' == $loginUser['is_admin'] ) {
        	return self::$_isAdmin = true;
        }
        Log::notice('_isAdmin===========>> loginUser=' . json_encode($loginUser));
         
        return self::$_isAdmin = false;
    }
    
    //是否是二级审核人
    private static $_isSecondAuditUser = NULL;
    public static function isSecondAuditUser(){
    
    	if(NULL !== self::$_isSecondAuditUser){
    		return self::$_isSecondAuditUser;
    	}
    	if(!self::isAdmin()){
    		return self::$_isSecondAuditUser = false;
    	}    	
    	$loginUser = self::getLoginUser();    	
    	if(!empty($loginUser) && is_array($loginUser) && $loginUser['usercode'] == $loginUser['managerid']){
    		self::$_isSecondAuditUser = true;
    	}else{
    		self::$_isSecondAuditUser = false;
    	}
    	    	
    	return self::$_isSecondAuditUser;
    }
    
    private static $_isLogin = NULL;
    public static function isLogin()
    {    	
    	if(NULL !== self::$_isLogin){
    		return self::$_isLogin;
    	}
    	$loginUser = self::getLoginUser();
    	if(empty($loginUser)){
    		return self::$_isLogin = false;
    	}
        return self::$_isLogin = true;        
    }
    
    protected function loginOut(){
        Log::notice("admin loginOut str .");
        try{
        	                        
            $response = $this->model('admin')->loginOut();
            if($response['code'] !== EC_OK){
            	Log::error('Adming Logout error '.$response['code']);
            }
            
            $session = $this->instance('session');
            $session->delete(self::$adminSessionKey);
            $session->clear();
            $session->destroy();
             
            $cookie = $this->instance('cookie');
            $cookie->clear(Router::getBaseUrl());
            
            self::$_isLogin = NULL;
            EC::success(EC_OK);
            
        } catch (Exception $e) {
            Log::error('loginOut . e=' . $e->getMessage());
        }
        Log::notice("admin loginOut end .");
        self::$_isLogin = NULL;
        
        EC::success(EC_OK);
    }
    
    private function login_old(){
        $account	=	Request::post('account');
        $password	=	Request::post('password');
        $pincode	=	Request::post('pincode');
        $login_csrf	=	Request::post('login_csrf');
        $other_csrf	=	Request::post('other_csrf');
        
        $admin_model = $this->model('admin');
        $data = $admin_model->login(array('account' => $account,'password' => $password));
        
        if(EC_OK != $data['code']){
           Log::error('login failed !');
           EC::fail($data['code']);
        }
        
        Log::notice('login completed . data=##' . json_encode($data) . '##');
        $loginUser = $data['data'];
        AdminController::setLoginSession($loginUser);
        EC::success(EC_OK);
    }
    
    protected function changePwd(){
        $old_pwd = $this->post('old_pwd');
        $new_pwd = $this->post('new_pwd');
        if ( !$old_pwd || !$new_pwd ) {
            Log::error('change password params error!');
            EC::fail(EC_PAR_ERR);
        }
         
        $admin_model = $this->model('admin');
        $params = array();
        $params['new_pwd'] = $new_pwd;
        $params['old_pwd'] = $old_pwd;
         
        $session = Controller::instance('session');
        $id = $session->get(self::$adminSessionKey)['id'];
        $params['id'] = $id;
         
        $data = $admin_model->changePwd( $params);
        if(EC_OK != $data['code']){
            Log::error('change password Fail!');
            EC::fail($data['code']);
        }
    
        EC::success(EC_OK);
    }
    
    private function login(){
        $account	=	Request::post('account');
        $password	=	Request::post('password');
        
        $admin_model = $this->model('admin');
        $data = $admin_model->erp_login(array('loginid' => $account,'userpwd' => $password));
        
        Log::notice('login completed . data=====111256===============>>>>=##' . json_encode($data) . '##');
        
        if(EC_OK != $data['code']){
           Log::error('login failed !');
           EC::fail($data['code']);
        }
        
        Log::notice('login completed . data=##' . json_encode($data) . '##');
        $loginUser = $data['data'];
        AdminController::setLoginSession($loginUser);
        EC::success(EC_OK);
    }
    
}