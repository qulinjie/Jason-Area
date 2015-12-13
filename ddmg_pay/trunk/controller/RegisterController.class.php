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

        $response ['code'] != EC_OK && EC::fail($response['code']);
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
        $uploadFile = $this->upload();
        if($uploadFile['code']){
            Log::error('doSecondStep upload file is fail mag('.$uploadFile['code'].')');
            EC::fail($uploadFile['code']);
        }

        $response = $this->model('user')->updatePersonalAuth(array(
            'id'       => $this->post('id'),
            'realName' =>  $this->post('name'),
            'fileName' => $uploadFile['name'],
            'filePath' => $uploadFile['path']
        ));

        $response['code'] != EC_OK && EC::fail($response['code']);

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
        $uploadFile = $this->upload();
        if($uploadFile['code']){
            Log::error('doThirdStep upload file is fail mag('.$uploadFile['code'].')');
            EC::fail($uploadFile['code']);
        }

        $response = $this->model('user')->updateCompanyAuth(array(
            'id'          => $this->post('id'),
            'legalPerson' => $this->post('legalPerson'),
            'companyName' => $this->post('companyName'),
            'license'     => $this->post('license'),
            'fileName'    => $uploadFile['name'],
            'filePath'    => $uploadFile['path']
        ));

        $response['code'] != EC_OK && EC::fail($response['code']);
        EC::success(EC_OK);
    }

    private function fourthStep()
    {
        $this->render('fourthStep');
    }

    private function upload()
    {
        $type = ['image/png','image/jpg','image/jpeg'];
        $res = ['code' => 0, 'path' => '', 'name' => ''];
        try{
            if($_FILES['file']['error']){
                $res['code'] = EC_OTH;
                return $res;
            }else if (!$_FILES['file']['name']) {
                $res['code'] = EC_UPL_FILE_NON;
                return $res;
            }else if(!in_array($_FILES['file']['type'],$type)){
                $res['code'] = EC_UPL_FILE_TYPE_ERR;
                return $res;
            }

            $res['name'] = $_FILES['file']['name'];
            $res['path'] = self::getAttachmentFilePath() . 'F_' . date('YmdHis', time()) . '_' . rand(999, 9999);
            move_uploaded_file($_FILES['file']['tmp_name'], $res['path']);
        }catch (Exception $e){
            Log::error('upload error msg ('.$e->getMessage().')');
            EC::fail(EC_OTH);
        }

        return $res;
    }

    private function getAttachmentFilePath()
    {
        return self::getConfig('conf')['attachment_file_path'];
    }

    private function sendCmsCode()
    {
        $response = $this->model('user')->sendCmsCode(['tel' => $this->post('tel'), 'type' => 1]);
        $response ['code'] != EC_OK && EC::fail($response['code']);
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