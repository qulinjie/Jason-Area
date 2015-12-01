<?php
/**
 * @file:  PassportController.class.php
 * @brief:  登陆退出控制器
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-18
 */

class PassportController extends BaseController
{

	public function handle( $params=[] )
	{
		if ( !$params ) {
			$this->login();
		} 
		else  switch ( $params[0] )
		{
			case 'login': // 登录
				$this-> login();
				break;
			case 'doLogin': // 登录
				$this-> doLogin();
				break;
			case 'ajaxDoLogin': // AJAX登录
				$this-> ajaxlogin();
				break;
			case 'logout': // 退出
				$this-> logout();
				break;
			case 'ajaxLogout': // AJAX登录
				$this-> ajaxLogin();
				break;
			case 'changePwd':
			    $this-> changePwd();
			    break;
			default:
				$this-> login();
				break;
		}
	}

	protected function changePwd()
	{
	    $old_pwd = $this->post('old_pwd');
	    $new_pwd = $this->post('new_pwd');
		if ( !$old_pwd || !$new_pwd ) {
	        Log::error('old password error. ');
	        EC::fail(EC_OLD_PWD_REC);
		}

		// 走CURL接口
		$res = $this->model( 'user' )->changePassword( $old_pwd, $new_pwd );
		if ( $res['code']==0 ) {
			EC::success(EC_OK);
		}else{
	        EC::fail($res['code']);
		}
	    
		/*
	    $session =  Controller::instance('session');
	    $id = $session->get( 'loginUser' )['id'];
	    $seller_model = $this->model( 'seller' );
	    
	    $loginUser = $seller_model->getSellerInfo(array('id'=>$id));
	    $inputPassword = md5($id.$old_pwd);
	    $password = $loginUser['password'];
	    if ( $password != $inputPassword ) {
	        Log::error('old password error. ');
	        EC::fail(EC_OLD_PWD_REC);
	    }
	    
	    $res = $seller_model->updateSeller(array('password'=>md5($id.$new_pwd)),array('id' => $id));
	    if(false === $res){
	        Log::error('change password error. ');
	        EC::fail(EC_UPD_REC);
	    }
	    EC::success(EC_OK);
		 */
	}
	
	protected function login(  )
	{
		if (self::isLogin()) {
			parent::redirect( Router::getBaseUrl() );
		}
		$data = [];
		$login_html = $this->render('login', $data, true);
		$this->render( 'index', array( 'page_type'=>'login', 'login_html'=>$login_html ) );
	}

	protected function doLogin()
	{
	    $account  = $this->post( 'account', '' );
	    if ( !$account ) {
	        Log::error('doLogin account empty. ');
	        EC::fail(EC_LOGIN_PAR_REC);
	    }
	    $password = $this->post( 'password', '' );
	    if ( !$password ) {
	        Log::error('doLogin password empty. ');
	        EC::fail(EC_LOGIN_PAR_REC);
	    }

		// 加CURL请求
		$userModel = $this->model( 'user' );
		$res = $userModel->login( $account, $password );
		if ( $res['code'] === 0 ) {

			//$user = $userModel->getUserByAccount( $account );
			$res =  Controller::model('user')->isLogin();	
			$user = $res['data'];
			unset( $user['password'] );
			$this->setLoginSession($user);

			EC::success(EC_OK);
		}else{
	        EC::fail($res['code']);
		}

	}

	protected function setLoginSession( $loginUser )
	{
		$session =  Controller::instance('session');	
		unset( $loginUser['password'] );
		return $session->set( 'loginUser', $loginUser );
	}

	protected function logout(  )
	{
		$res = $this->model( 'user' )->logout();
		if ( $res[code]===0 ) {

			$session =  Controller::instance('session');	
			$session->delete( 'loginUser' );

			// 删除CRUL COOKIE
			CurlModel::clearCRULRedis();

			EC::success(EC_OK);
		}
	    EC::fail(EC_LOG_OUT_ERR);

		/*
		$session =  Controller::instance('session');	
		$session->delete( 'loginUser' );
		EC::success(EC_OK);
		 */
	}

	protected function ajaxLogin(  )
	{
	}

	public static function checkLogin(  )
	{
		if (!self::isLogin()) {
			parent::redirect( Router::getBaseUrl().'passport' );
		}
		return true;
	}
	
	public static function isLogin(  )
	{
		$res =  Controller::model('user')->isLogin();	
		//$res['data'];
		if ( $res['code'] !== 0 ) {
			Log::error( 'server userController  isLogin error' );
			return false;
		}
		return true;
	}

/*
 *
 *    public static function isLogin(  )
 *    {
 *        $session =  Controller::instance('session');	
 *        return $session->get( 'loginUser' ) ? true: false;
 *    }
 *
 */

}
