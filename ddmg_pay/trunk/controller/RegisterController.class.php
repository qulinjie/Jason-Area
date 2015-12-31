<?php

/**
 * 用户注册
 */
class RegisterController extends BaseController
{
    public function handle($params = array())
    {
        switch ($params[0]) {
            case 'account'://填写帐号
                $this->account();
                break;
            case 'person'://填写个人信息
                $this->person();
                break;
            case 'enterprise'://填写企业信息
                $this->enterprise();
                break;
            case 'finish'://注册完成
                $this->finish();
                break;
            case 'sendCmsCode': //发送短信
                $this->sendCmsCode();
                break;
            default:
                Log::error('this method is not exists ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    public function account()
    {
        if (IS_POST) {
            $tel = $this->post('tel');
            $pwd = $this->post('pwd');
            $code = $this->post('code');
            if (!$tel || !$pwd || !$code) {
                Log::error('register account params miss');
                EC::fail(EC_PAR_ERR);
            }

            $response = $this->model('user')->register(['tel' => $tel, 'pwd' => $pwd, 'code' => $code]);
            $response ['code'] !== EC_OK && EC::fail($response['code']);
            $session = self::instance('session');
            $session->set('register_cert_id', $response['data']['register_certification_id']);
            EC::success(EC_OK);
        }

        $this->render('registerAccount');
    }

    private function person()
    {
        if (IS_POST) {
            $file = self::uploadFile();
            if ($file['code'] !== EC_OK) {
                Log::error('register person upload file is fail msg(' . $file['code'] . ')');
                EC::fail($file['code']);
            }

            $id = $this->post('id');
            $realName = $this->post('name');
            if(!$id || !$realName){
                Log::error('register person params miss ');
                EC::fail(EC_PAR_BAD);
            }

            $response = $this->model('user')->updatePersonalAuth(array(
                'id' => $id,
                'realName' => $realName,
                'fileName' => $file['fileName'],
                'filePath' => $file['filePath']
            ));

            $response['code'] !== EC_OK && EC::fail($response['code']);
            //再刷一次，防止session过期
            $session = self::instance('session');
            $session->set('register_cert_id', $id);

            EC::success(EC_OK);
        }

        $session = self::instance('session');
        $this->render('registerPerson', array('id' => $session->get('register_cert_id')));
    }

    private function enterprise()
    {
        if (IS_POST) {
            $file = self::uploadFile();
            if ($file['code'] !== EC_OK) {
                Log::error('doThirdStep upload file is fail msg(' . $file['code'] . ')');
                EC::fail($file['code']);
            }

            $id          = $this->post('id');
            $license     = $this->post('license');
            $legalPerson = $this->post('legalPerson');
            $companyName = $this->post('companyName');
            if(!$id || !$license || !$legalPerson || !$companyName){
                Log::error('register enterprise params miss ');
                EC::fail(EC_PAR_BAD);
            }

            $response = $this->model('user')->updateCompanyAuth(array(
                'id'          => $id,
                'license'     => $license,
                'legalPerson' => $legalPerson,
                'companyName' => $companyName,
                'fileName'    => $file['fileName'],
                'filePath'    => $file['filePath']
            ));

            $response['code'] !== EC_OK && EC::fail($response['code']);
            EC::success(EC_OK);
        }
        $session = self::instance('session');
        $this->render('registerEnterprise', array('id' => $session->get('register_cert_id')));
    }

    private function finish()
    {
        $this->render('registerFinish');
    }

    private function sendCmsCode()
    {
        if (!$tel = $this->post('tel')) {
            Log::error('sendCmsCode tel is empty');
            EC::fail(EC_PAR_BAD);
        }
        $response = $this->model('user')->sendCmsCode(['tel' => $this->post('tel'), 'type' => 1]);
        $response ['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    public static function filter()
    {
        return [
            'token' => ['account', 'person', 'enterprise', 'sendCmsCode'], //需要token验证
        ];
    }
}