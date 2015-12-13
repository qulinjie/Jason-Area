<?php


class UserController extends BaseController
{
    public function handle($params = array())
    {
        if (!$params) {
            Log::error('Login params is empty');
            EC::fail(EC_MTD_NON);
        }

        switch ($params[0]) {
            case 'login':
                $this->login();
                break;
            case 'doLogin':
                $this->doLogin();
                break;
            case 'logout':
                $this->logout();
                break;
            default:
                Log::error('this method is not exists ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    private function login()
    {
        self::isLogin()&&$this->redirect(Router::getBaseUrl());
        $this->render('login');
    }

    private function doLogin()
    {
        $response = $this->model('user')->login(['tel' => $this->post('account'), 'pwd' => $this->post('password')]);
        $response['code'] != EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    private function logout()
    {
        $response = $this->model('user')->logout();
        $response['code'] != EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    /**
     * @return bool
     */
    public static function isLogin()
    {
        $response = self::model('user')->isLogin();
        return $response['code'] == EC_OK && $response['data']['isLogin'];
    }

    /**
     * @return array
     */
    public static function getLoginUser()
    {
        $response = self::model('user')->getLoginUser();
        return $response['code'] == EC_OK ? $response['data']['loginUser'] : [];
    }

    /**
     * @return string
     */
    public static function getToken()
    {
        $session = self::instance('session');
        $encrypt = self::instance('encrypt');
        $session->set('_token',$encrypt->randCode(16));
        return $encrypt->tokenCode($session->get('_token'),$session::getTimeout());
    }

    /**
     * @param null $token
     */
    public static function checkToken($token = null)
    {
        $session = self::instance('session');
        $encrypt = self::instance('encrypt');
        if(!$_token = $session->get('_token')){
            Log::error('token expire ('.json_encode(doit::$params).')');
            EC::fail(EC_TOKEN_EXP);
        }else if(!$encrypt::tokenValidate($_token,$token,$session::getTimeout())){
            Log::error('token error ('.json_encode(doit::$params).')');
            EC::fail(EC_TOKEN_ERR);
        }
    }

    public static function filter()
    {
        return [
            'token' => ['doLogin','logout'], //需要token验证
            'login' => ['logout']            //需要登录验证
        ];
    }

    /**
     * 验证不通过，则调用EC::fail
     */
    public function init()
    {
        foreach(self::filter() as $key => $actList){
            if(in_array(doit::$params[0],$actList)){
                switch($key) {
                    case 'token':
                        self::checkToken($this->post('token'));//默认 post
                        break;
                    case 'login':
                        !self::isLogin() && EC::fail(EC_NOT_LOGIN);
                        break;
                }
            }
        }
    }
}