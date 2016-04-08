<?php

class ApiController extends Controller {
	
	public function handle($params = array()) {}
	
	//判断是否是api方式访问
	public static function isApi(){
		if(defined('IN_API')){
			return true;
		}
		return false;
	}
	
	//获取get/post提交的loginkey
	private static $_loginkey = NULL;
	public static function getLoginkey(){		
		if(NULL !== self::$_loginkey){
			return self::$_loginkey;
		}		
		$loginkey = NULL;
		if(self::isApi()){
			//支持get/post提交loginkey,优先支持get方式
			$loginkey = Request::get(UserController::$_loginKeyName);
			if(empty($loginkey)){
				$loginkey = Request::post(UserController::$_loginKeyName);
			}			
		}
		if(!empty($loginkey)){
			return self::$_loginkey = $loginkey;
		}
		return $loginkey;
	}
	
	//将loginkey的md5值作为session_id
	private static $_session_id = NULL;
	public static function getSessionId(){
		if(NULL !== self::$_session_id){
			return self::$_session_id;
		}
		if(true && $loginkey = self::getLoginkey()){			
			return self::$_session_id = md5($loginkey);
		}
		return NULL;
	}
	
	//设置session_id
	public static function setSessionId(){
		$session_id = self::getSessionId();
		if(self::isApi() && !empty($session_id)){
			if(session::get_id() != $session_id){
				//echo "<br>----setSessionId()----<br>";
				session::set_id(self::getSessionId());
				return true;
			}
		}
		return false;	
	}
	
	//对api方式访问的进行loginkey方式登录user
	public static function login(){		
		if(self::isApi() && !empty(self::getLoginkey()) && !UserController::isLogin()){
			//echo "<br>---- loginByLoginkey ----<br>";
			UserController::loginByLoginkey(self::getLoginkey());
		}		
	}
	
	//api接口调用安全验证
	public static function securityValidate(){
		
	}
	
}