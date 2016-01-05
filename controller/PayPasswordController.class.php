<?php


class PayPasswordController extends BaseController
{
    public function handle($params = array())
    {
        switch ($params[0]) {
            case 'reset':
                $this->reset();
                break;
            case 'notice':
                $this->notice();
                break;
            default:
                Log::error('PayPasswordController method not exists ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    private function reset()
    {
        if(IS_POST){
            $params['newPwd'] = $this->post('newPwd');
            self::check() && $params['oldPwd']=$this->post('oldPwd');
            foreach($params as $val){
                if(!$val){
                    Log::error('payPassword reset params miss');
                    EC::fail(EC_PAR_BAD);
                }
            }

            $response = $this->model('payPassword')->passwordReset($params);
            $response['code'] !== EC_OK && EC::fail($response['code']);
            EC::success(EC_OK);
        }
        $password_html = $this->render('payPasswordReset',['status' => self::check()],true);
        $this->render('index',['page_type' => 'payPassword','password_html' => $password_html]);
    }

    private function notice()
    {
        $password_html = $this->render('payPasswordNotice',[],true);
        $this->render('index',['page_type' => 'payPassword','password_html' => $password_html]);
    }

    public static function check()
    {
        $response = self::model('payPassword')->check();
        if($response['code'] !== EC_OK){
            Log::error('payPassword check error code='.$response['code']);
            EC::fail_page($response['code']);
        }

        return $response['data']['status'];
    }

    public static function verify($payPassword = '')
    {
        if(!$payPassword) return false;
        $response = self::model('payPassword')->validatePassword(['payPassword' => $payPassword]);
        if($response['code'] !== EC_OK){
            Log::error('payPassword password verify error msg:'.$response['msg']);
            return false;
        }

        return $response['data']['status'];
    }

    public static function filter()
    {
        return [
            'token' => ['passwordReset'],
            'login' => ['passwordReset']
        ];
    }
}