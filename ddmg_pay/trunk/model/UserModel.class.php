<?php
/**
 * @file:  UserModel.class.php
 * @brief:  走CURL接口在，MODEL类
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-09-11
 */


class UserModel extends CurlModel
{
	public function login( $account, $password )
	{
		if ( !$account || !$password ) { 
			return false;
		}
		$data['tel'] = $account;
		$data['pwd'] = $this->ppwd($password);
		$data['is_web_login'] = 1;
		$interface = 'user/login';
		return $this-> sendRequest( $interface, $data );
	}

	public function isLogin()
	{
		$interface = 'user/sellerIsLogin';
		return $this-> sendRequest( $interface, [] );
	}

	public function logout(  )
	{
		$interface = 'user/log_out'; // 干吊加个下划线！
		return $this-> sendRequest( $interface, $data );
		/*
		 *$url = $this->getUrl( $interface );
		 *$data['caller'] = 'test';
		 *$data['callee'] = 'ddmg_server';
		 *$data['eventid'] = rand() % 10000;
		 *$data['timestamp'] = time();
		 *return json_decode( $this->send( $url, $data ), true );
		 */
	}

	public function changePassword( $old_pwd, $new_pwd )
	{
		if ( !$old_pwd || !$new_pwd ) {
			return false;
		}
		$data['cur_pwd'] = $this->ppwd($old_pwd);
		$data['new_pwd'] = $this->ppwd($new_pwd);
		$interface = 'user/reset_pwd'; // 干吊加个下划线！
		return $this->sendRequest( $interface, $data );
		/*
		$interface = 'user/reset_pwd'; // 干吊加个下划线！
		$url = $this->getUrl( $interface );
		$data['data']['cur_pwd'] = $this->ppwd($odl_pwd);
		$data['data']['new_pwd'] = $this->ppwd($new_pwd);
		$data['caller'] = 'test';
		$data['callee'] = 'ddmg_server';
		$data['eventid'] = rand() % 10000;
		$data['timestamp'] = time();
		
		return json_decode($this->send( $url, $data ), true);
		 */
	}

	/**
	 * @brief:  加密数据
	 * @param:  $password
	 * @return:  
	 */
	protected function ppwd( $password )
	{
		$public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnqqHspRsIm9MlnGiEdpnux3D5
G9jrVqYP7gl+OuhtZKxhp1CiQuKxmBkiF5YdWGutAzBdA0hWd4k+vbTSDuJmVcIa
krb0/MkQxbg1YPjVjtBv7i0sJJOFv/A0oLNEJjuyiOMWSv30d2VkvU/3of/mnW33
Kb/4PN/nOI8h1rj0IQIDAQAB
-----END PUBLIC KEY-----';
		$pu_key = openssl_pkey_get_public($public_key);
		$crypted = '';
		openssl_public_encrypt($password, $crypted,$pu_key);
		$input = base64_encode($crypted);
		return $input;
	}

	public function getUserByAccount( $account )
	{
		if ( !$account  ) {
			return false;
		}
		$data['tel'] = $account;
		$interface = 'user/sellerGetBuyerByTel';
		return $this-> sendRequest( $interface, $data );
	}

	public function getUserById( $uid )
	{
		if ( !$uid  ) { 
			return false;
		}
		$data['user_id'] = $uid;
		$interface = 'user/sellerGetUserById';
		return $this-> sendRequest( $interface, $data );
	}


}
