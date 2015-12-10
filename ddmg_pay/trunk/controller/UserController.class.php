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
        $this->render('login');
    }

    private function doLogin()
    {
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
        return Session::is_set('loginUser');
    }

    public static function getLoginUser()
    {
        $session = self::instance('session');
        return $session->get('loginUser');
    }
}