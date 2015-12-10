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
        self::isLogin()&&EC::success(EC_OK);
        $response = $this->model('user')->login(['tel' => $this->post('account'), 'pwd' => $this->post('password')]);
        $response['code'] != EC_OK && EC::fail($response['code']);
        $this->setLoginSession($response['data']);
        EC::success(EC_OK);
    }

    private function logout()
    {
        $response = $this->model('user')->logout();
        $response['code'] != EC_OK && EC::fail($response['code']);
        $this->setLoginSession();
        EC::success(EC_OK);
    }

    private function setLoginSession($userInfo = array())
    {
        $session = self::instance('session');
        $session->clear();
        $userInfo && $session->set('loginUser', $userInfo);
    }

    public static function isLogin()
    {
        $session = self::instance('session');
        return $session::is_set('loginUser');
    }

    public static function getLoginUser()
    {
        $session = self::instance('session');
        return $session->get('loginUser');
    }
}