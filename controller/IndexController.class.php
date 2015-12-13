<?php
class IndexController extends Controller 
{
	public function handle( $params=[] ) 
	{
		$this->display('index');
	}

	public function init()
	{
		if(!UserController::isLogin()){
			Log::error('not login ('.json_encode(doit::$params).')');
			$this->redirect($this->getBaseUrl().'user/login');
		}
		return true;
	}
}
