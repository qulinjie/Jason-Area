<?php


class UserController extends BaseController
{
    public function handle($params = array())
    {
        switch ($params[0]) {
            case 'login':
                $this->login();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'getPinCode':
                $this->getPinCode();
                break;
            case 'passwordReset':
                $this->passwordReset();
                break;
            default:
                Log::error('UserController method not exists ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    private function login()
    {
        if(IS_POST){
            $tel  = $this->post('account');
            $pwd  = $this->post('password');
            $code = $this->post('pinCode');
            (!$tel || !$pwd || !$code) && EC::fail(EC_PAR_BAD);

            $pinCode = self::instance('pincode');
            !$pinCode->check($code) && EC::fail(EC_PINCODE_ERR);

            $response = $this->model('user')->login(['tel' => $tel, 'pwd' => $pwd]);
            $response['code'] !== EC_OK && EC::fail($response['code']);
            EC::success(EC_OK);
        }
        self::isLogin()&&$this->redirect(Router::getBaseUrl());
        $this->render('login');
    }

    private function logout()
    {
        $response = $this->model('user')->logout();
        if($response['code'] !== EC_OK){
            Log::error('User Logout error '.$response['code']);
        }
        EC::success(EC_OK);
    }

    private function getPinCode()
    {
        $pinCode = self::instance('pincode');
        $pinCode->setImageSize(71,41);
        $pinCode->show();
    }

    private function passwordReset()
    {
        if(IS_POST){
            $oldPwd = $this->post('oldPwd');
            $newPwd = $this->post('newPwd');
            if(!$oldPwd || !$newPwd){
                Log::error('loginPassword reset params miss');
                EC::fail(EC_PAR_BAD);
            }

            $response = $this->model('user')->loginPasswordReset(['oldPwd' => $oldPwd,'newPwd' => $newPwd]);
            $response['code'] !== EC_OK && EC::fail($response['code']);
            EC::success(EC_OK);
        }

        $password_html = $this->render('loginPasswordReset',[],true);
        $this->render('index',['page_type' => 'user','password_html' =>$password_html]);
    }

    public static function isLogin()
    {
        $response = self::model('user')->isLogin();
        return $response['code'] === EC_OK && $response['data']['isLogin'];
    }

    public static function getLoginUser()
    {
        $response = self::model('user')->getLoginUser();
        return $response['code'] === EC_OK ? $response['data']['loginUser'] : [];
    }

    public static function getToken()
    {
        $session = self::instance('session');
        $encrypt = self::instance('encrypt');
        $session->set('_token',$encrypt->randCode(16));
        return $encrypt->tokenCode($session->get('_token'));
    }

    public static function checkToken($token = null)
    {
        $session = self::instance('session');
        $encrypt = self::instance('encrypt');
        if(!$encrypt::tokenValidate($session->get('_token'),$token)){
            Log::error('token expire ('.json_encode(doit::$params).')');
            EC::fail(EC_TOKEN_EXP);
        }
    }

    public static function filter()
    {
        return [
            'token' => ['login','passwordReset'],
            'login' => ['passwordReset']
        ];
    }
}