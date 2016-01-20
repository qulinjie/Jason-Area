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
            if(!$newPwd = self::decrypt($this->post('newPwd'))){
                Log::error('payPassword reset params error');
                EC::fail(EC_PAR_BAD);
            }
            
            if(self::check()){
                if(!$oldPwd = self::decrypt($this->post('oldPwd'))){
                    Log::error('payPassword reset params error');
                    EC::fail(EC_PAR_BAD);
                }
                
                if(!self::verify($oldPwd)){
                    Log::error('PayPassword reset oldPwd error');
                    EC::fail(EC_PWD_WRN);
                }
            }
            
            if(!$build = password_hash($newPwd, PASSWORD_DEFAULT)){
                Log::error('PayPassword reset build error newPwd='.$newPwd);
                EC::fail(EC_OTH);
            }
            
            $loginUser = UserController::getLoginUser();            
            $data = $this->model('user')->update(array('id' => $loginUser['id'], 'pay_password' => $build));
            $data['code'] !== EC_OK && EC::fail($data['code']);           
                      
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
        if(!$loginUser = UserController::getLoginUser()){
            Log::error('PayPassword check not login');
            EC::fail(EC_NOT_LOGIN);
        }

        return $loginUser['pay_password'] == true;
    }

    public static function verify($payPassword = '')
    {
        $loginUser = UserController::getLoginUser();
        if(!$payPassword || !$loginUser){
            Log::error('PayPassword verify error');
            return false;
        }
       
        if(true !== password_verify($payPassword, $loginUser['pay_password'])){
            Log::error('payPassword password verify error');
            Log::error('payPassword password payPassword='.$payPassword);
            Log::error('payPassword password id='.$loginUser['id']);
            return false;
        }

        return true;
    }

    public static function filter()
    {
        return [
            'token' => ['passwordReset'],
            'login' => ['passwordReset']
        ];
    }
}