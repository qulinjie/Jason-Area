<?php


class RegisterController extends BaseController
{
    public function handle($params = array())
    {
        if (!$params) {
            Log::error('register params is empty');
            EC::fail(EC_MTD_NON);
        }

        switch ($params[0]) {
            case 'firstStep':
                $this->firstStep();
                break;
            case 'doFirstStep':
                $this->doFirstStep();
                break;
            case 'secondStep':
                $this->secondStep();
                break;
            case 'doSecondStep':
                $this->doSecondStep();
                break;
            case 'thirdStep':
                $this->thirdStep();
                break;
            case 'doThirdStep':
                $this->doThirdStep();
                break;
            case 'fourthStep':
                $this->fourthStep();
                break;
            case 'sendCmsCode':
                $this->sendCmsCode();
                break;
            default:
                Log::error('this method is not exists ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    public function firstStep()
    {
        $this->render('firstStep');
    }

    private function doFirstStep()
    {
        $response = $this->model('user')->register([
            'tel' => $this->post('tel'),
            'pwd' => $this->post('pwd'),
            'code'=> $this->post('code')
        ]);

        $response ['code'] !== EC_OK && EC::fail($response['code']);
        $session = self::instance('session');
        $session->set('certification_id', $response['data']['certification_id']);
        EC::success(EC_OK);
    }


    private function secondStep()
    {
        $session = self::instance('session');
        $this->render('secondStep',array('id' => $session->get('certification_id')));
    }

    private function doSecondStep()
    {
        $uploadFile = self::uploadFile();
        if($uploadFile['code'] !== EC_OK){
            Log::error('doSecondStep upload file is fail msg('.$uploadFile['code'].')');
            EC::fail($uploadFile['code']);
        }

        $response = $this->model('user')->updatePersonalAuth(array(
            'id'       => $this->post('id'),
            'realName' =>  $this->post('name'),
            'fileName' => $uploadFile['fileName'],
            'filePath' => $uploadFile['filePath']
        ));

        $response['code'] !== EC_OK && EC::fail($response['code']);

        //再刷一次，防止session过期
        $session = self::instance('session');
        $session->set('certification_id', $this->post('id'));

        EC::success(EC_OK);
    }

    private function thirdStep()
    {
        $session = self::instance('session');
        $this->render('thirdStep',array('id' => $session->get('certification_id')));
    }

    private function doThirdStep()
    {
        $uploadFile = self::uploadFile();
        if($uploadFile['code'] !== EC_OK){
            Log::error('doThirdStep upload file is fail msg('.$uploadFile['code'].')');
            EC::fail($uploadFile['code']);
        }

        $response = $this->model('user')->updateCompanyAuth(array(
            'id'          => $this->post('id'),
            'legalPerson' => $this->post('legalPerson'),
            'companyName' => $this->post('companyName'),
            'license'     => $this->post('license'),
            'fileName'    => $uploadFile['fileName'],
            'filePath'    => $uploadFile['filePath']
        ));

        $response['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    private function fourthStep()
    {
        $this->render('fourthStep');
    }

    private function sendCmsCode()
    {
        $response = $this->model('user')->sendCmsCode(['tel' => $this->post('tel'), 'type' => 1]);
        $response ['code'] !== EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    public static function filter()
    {
        return [
            'token' => ['doFirstStep','doSecondStep','doThirdStep','sendCmsCode'], //需要token验证
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
                        UserController::checkToken($this->post('token'));//默认 post
                        break;
                }
            }
        }
    }
}