<?php

/**
 * 用户注册
 */
class RegisterController extends BaseController
{
    public function handle($params = array())
    {   
        exit('403 forbidden');
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

        $this->render('layout',['content' => $this->render('registerAccount',[],true)]);
    }

    private function person()
    {
        if (IS_POST) {
            $file = self::uploadFile();
            if ($file['code'] !== EC_OK) {
                Log::error('register person upload file is fail msg(' . $file['code'] . ')');
                EC::fail($file['code']);
            }
            
            $params['id']                   = $this->post('id');
            $params['nicename']             = $this->post('name');
            $params['certificate_filename'] = $file['fileName'];
            $params['certificate_filepath'] = $file['filePath'];
            
            foreach ($params as $val){
                if(!$val){
                    Log::error('register person params miss ');
                    EC::fail(EC_PAR_BAD);
                }
            }        
            
            $data = $this->model('cert')->update($params);
            $data['code'] !== EC_OK && EC::fail($data['code']);
            
            unset($params['certificate_filename'],$params['certificate_filepath']);
            $params['personal_authentication_status'] = 2;

            $data = $this->model('user')->update($params);
            $data['code'] !== EC_OK && EC::fail($data['code']);
            
            
            //再刷一次，防止session过期
            $session = self::instance('session');
            $session->set('register_cert_id', $params['id']);

            EC::success(EC_OK);
        }

        $session = self::instance('session');
        $content = $this->render('registerPerson', array('id' => $session->get('register_cert_id')),true);
        $this->render('layout',['content' => $content]);
    }

    private function enterprise()
    {
        if (IS_POST) {
            $file = self::uploadFile();
            if ($file['code'] !== EC_OK) {
                Log::error('doThirdStep upload file is fail msg(' . $file['code'] . ')');
                EC::fail($file['code']);
            }

            $params['id']               = $this->post('id');
            $params['legal_name']       = $this->post('legalPerson');
            $params['company_name']     = $this->post('companyName');
            $params['business_license'] = $this->post('license');                       
            $params['business_license_filename'] = $file['fileName'];
            $params['business_license_filepath'] = $file['filePath'];            
        
            foreach ($params as $val){
                if(!$val){
                    Log::error('register enterprise params miss ');
                    EC::fail(EC_PAR_BAD);
                }
            }
            
            $data = $this->model('cert')->update($params);
            $data['code'] !== EC_OK && EC::fail($data['code']);
            
            unset($params['legal_name'], $params['business_license'],$params['business_license_filename'], $params['business_license_filepath']);
            $params['company_authentication_status'] = 2 ;
            $data = $this->model('user')->update($params);

            $data['code'] !== EC_OK && EC::fail($data['code']);
            EC::success(EC_OK);
        }
        $session = self::instance('session');
        $content =  $this->render('registerEnterprise', array('id' => $session->get('register_cert_id')),true);
        $this->render('layout',['content' => $content]);
    }

    private function finish()
    {
        $content = $this->render('registerFinish',[],true);
        $this->render('layout',['content' => $content]);
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