<?php

/**
 * 找回密码
 */

class FindPasswordController extends BaseController
{
    public function handle($params = array())
    {
        switch ($params[0]) {
            case 'account':
                $this->account();
                break;
            case 'verify':
                $this->verify();
                break;
            case 'reset':
                $this->reset();
                break;
            case 'finish':
                $this->finish();
                break;
            case 'getPinCode':
                $this->getPinCode();
                break;
            case 'sendCmsCode':
                $this->sendCmsCode();
                break;
            default:
                Log::error('this method is not exists ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    private function getPinCode()
    {
        $pinCode = self::instance('pincode');
        $pinCode->setImageSize(65,41);
        $pinCode->show();
    }

    private function account()
    {
        if(IS_POST){
            $tel = $this->post('tel');
            $pinCode = self::instance('pincode');
            (!$tel || !$pinCode) && EC::fail(EC_PAR_BAD);
            !$pinCode->check($this->post('pinCode')) && EC::fail(EC_PINCODE_ERR);

            $response = $this->model('findPassword')->check(array('tel' => $tel));
            $response['code'] !== EC_OK && EC::fail($response['code']);

            $session = self::instance('session');
            $session->set('findPasswordTel',$tel);
            EC::success(EC_OK);
        }
        $this->render('findPasswordAccount');
    }

    private function verify()
    {
        if(IS_POST){
            $file = self::uploadFile();
            if($file['code'] !== EC_OK){
                Log::error('findPassword verify upload file is fail msg('.$file['code'].')');
                EC::fail($file['code']);
            }

            $name    = $this->post('name');
            $tel     = $this->post('tel');
            $code    = $this->post('code');
            (!$tel || !$name || !$code) && EC::fail(EC_PAR_BAD);

            $response = $this->model('findPassword')->identity(array('real_name'=>$name,'tel'=>$tel,'code'=>$code));
            if($response['code'] !== EC_OK){
                $session = self::instance('session');
                $session->delete('findPasswordTel');
                Log::error('findPassword verify error msg('.$response['msg'].')');
                EC::fail(EC_CERT_ERR); //密码更新失败
            }
            EC::success(EC_OK);
        }
        $session = self::instance('session');
        $tel = $session->get('findPasswordTel');
        !$tel && $this->redirect(Router::getBaseUrl());//默认返回主页
        $this->render('findPasswordVerify',array('tel' => $tel));
    }

    private function reset()
    {
        if(IS_POST){
            $tel = $this->post('tel');
            $pwd = $this->post('pwd');
            (!$tel || !$pwd) && EC::fail(EC_PAR_BAD);

            $response = $this->model('findPassword')->loginPasswordReset(['tel' => $tel,'pwd' => $pwd]);
            if($response['code'] !== EC_OK){
                $session = self::instance('session');
                $session->delete('findPasswordTel');
                Log::error('findPassword reset error msg('.$response['msg'].')');
                EC::fail(EC_PWD_UPD); //密码更新失败
            }
            EC::success(EC_OK);
        }
        $session = self::instance('session');
        $tel = $session->get('findPasswordTel');
        !$tel && $this->redirect(Router::getBaseUrl());//默认返回主页
        $this->render('findPasswordReset',array('tel' => $tel));
    }

    private function finish()
    {
        $session  = self::instance('session');
        $viewName = $session->get('findPasswordTel') ? 'findPasswordSuccess' : 'findPasswordFail';
        $session->delete('findPasswordTel');
        $this->render($viewName);
    }

    private function sendCmsCode()
    {
        $response = $this->model('user')->sendCmsCode(['tel' => $this->post('tel'), 'type' => 2]);
        $response ['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    public static function filter()
    {
        return [
            'token' => ['account','verify','reset','sendCmsCode'], //需要token验证
        ];
    }
}