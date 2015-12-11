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

    public static function isLogin()
    {
        $response = self::model('user')->isLogin();
        return $response['code'] == EC_OK && $response['data']['isLogin'];
    }

    public static function getLoginUser()
    {
        $response = self::model('user')->getLoginUser();
        return $response['code'] == EC_OK ? $response['data']['loginUser'] : [];
    }

    public static function getLoginToken()
    {
        $session = self::instance('session');
        $encrypt = self::instance('encrypt');
        return $encrypt->tokenCode('login:' . $session->get_id());
    }

    public static function getOtherToken()
    {
        $session = self::instance('session');
        $encrypt = self::instance('encrypt');
        return $encrypt->tokenCode('other:' . $session->get_id());
    }
}