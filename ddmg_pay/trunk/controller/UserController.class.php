<?php


class UserController extends BaseController
{
    public function handle($params = array())
    {
        if (!$params) {
            Log::error('UserController params is empty');
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
            case 'getPinCode':
                $this->getPinCode();
                break;
            case 'findPassword':
                $this->findPassword();
                break;
            case 'doFindPassword':
                $this->doFindPassword();
                break;
            case 'findPasswordMsg':
                $this->findPasswordMsg();
                break;
            case 'setPassword':
                $this->setPassword();
                break;
            case 'sendCmsCode':
                $this->sendCmsCode();
                break;
            case 'unSetPayPassword':
                $this->unSetPayPassword();
                break;
            case 'setPayPassword':
                $this->setPayPassword();
                break;
            case 'doSetPayPassword':
                $this->doSetPayPassword();
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
        $pinCode = self::instance('pincode');
        !$pinCode->check($this->post('pinCode')) && EC::fail(EC_PINCODE_ERR);

        $response = $this->model('user')->login(['tel' => $this->post('account'), 'pwd' => $this->post('password')]);
        $response['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    private function logout()
    {
        $response = $this->model('user')->logout();
        $response['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    private function getPinCode()
    {
        $pinCode = self::instance('pincode');
        $pinCode->setImageSize(70,34);
        $pinCode->show();
    }

    private function findPassword()
    {
        $this->render('findPassword');
    }

    private function doFindPassword()
    {
        $uploadFile = self::uploadFile();
        if($uploadFile['code'] !== EC_OK){
            Log::error('doThirdStep upload file is fail msg('.$uploadFile['code'].')');
            EC::fail($uploadFile['code']);
        }

        $response = $this->model('user')->addFindPassword(array(
            'account'       => $this->post('account'),
            'name'          => $this->post('name'),
            'tel'           => $this->post('tel'),
            'code'          => $this->post('code'),
            'auth_filename' => $uploadFile['fileName'],
            'auth_filepath' => $uploadFile['filePath']
        ));

        $response['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    private function findPasswordMsg()
    {
        $this->render('findPasswordMsg');
    }

    private function setPassword()
    {
        $response = $this->model('user')->setPassword(array(
            'oldPwd' => $this->post('oldPwd'),
            'newPwd' => $this->post('newPwd')
        ));

        $response['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    private function sendCmsCode()
    {
        $response = $this->model('user')->sendCmsCode(['tel' => $this->post('tel'), 'type' => 2]);
        $response ['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    private function unSetPayPassword()
    {
        $payPassword_html = $this->render('unSetPayPassword',[],true);
        $this->render('index',['page_type' => 'User','payPassword_html'=>$payPassword_html]);
    }

    private function setPayPassword()
    {
        $payPassword_html = $this->render('setPayPassword',[],true);
        $this->render('index',['page_type' => 'User','payPassword_html'=>$payPassword_html]);
    }

    private function doSetPayPassword()
    {
        $response = $this->model('user')->setPayPassword(['payPassword' => $this->post('password')]);
        $response['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }
    /**
     * @return bool
     */
    public static function isLogin()
    {
        $response = self::model('user')->isLogin();
        return $response['code'] === EC_OK && $response['data']['isLogin'];
    }

    /**
     * @return array
     */
    public static function getLoginUser()
    {
        $response = self::model('user')->getLoginUser();
        return $response['code'] === EC_OK ? $response['data']['loginUser'] : [];
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
        if(!$encrypt::tokenValidate($session->get('_token'),$token,$session::getTimeout())){
            Log::error('token expire ('.json_encode(doit::$params).')');
            EC::fail(EC_TOKEN_EXP);
        }
    }

    /**
     * @return bool
     */
    public static function isSetPayPassword()
    {
        $response = self::model('user')->isSetPayPassword();
        $response['code'] !== EC_OK && EC::fail($response['code']);
        return $response['data']['isSet'];
    }

    public static function filter()
    {
        return [
            'token' => ['doLogin','doFindPassword','sendCmsCode','setPassword','doSetPayPassword'], //需要token验证
            'login' => ['logout','setPassword','doSetPayPassword']            //需要登录验证
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