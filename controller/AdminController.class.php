<?php
class AdminController extends Controller {

    public function handle($params = array()) {
        if(empty($params)){
            Log::error ('AdminController . params is empty . ');
            EC::fail (EC_MTD_NON);
        }else {
            switch ($params[0]){
                case 'mgr':
                    $this->display('index');
                    break;
                case 'login':
                    $this->login();
                    break;
                case 'loginOut':
                    $this->loginOut();
                    break;
                case 'changePwd':
                    $this->changePwd();
                    break;
                default :
                   Log::error ('page not found . ' . $params[0]);
                   EC::fail (EC_MTD_NON);
                   break;
            }
        }
    }
    
    protected static function setLoginSession($loginUser){
        if(empty($loginUser)){
            Log::error('setLoginSession [loginUser] is empty .');
            return false;
        }
        $session = Controller::instance('session');
        unset( $loginUser['password'] );
        $session->set('loginUser', $loginUser);
        Log::notice('setLoginSession==>>sessionId=' . $session->get_id() . ' ,loginUser=' . json_encode($loginUser) );
        // check
        Log::notice('check setLoginSession . is_set[loginUser]=' . ($session->is_set('loginUser')) );
        Log::notice('check setLoginSession . get[loginUser]=' . json_encode($session->get('loginUser')) );
        return true;
    }
    
    public static function isAdmin(){
        $session = Controller::instance('session');
        if($session->is_set('loginUser') ){
            if( 'yes' == $session->get('loginUser')['is_admin'] ) {
                return true;
            }
            Log::notice('isAdmin==================================>> loginUser=' . json_encode($session->get('loginUser')) );
        }
        return false;
    }
    
    public static function isLogin()
    {
        try{
            $admin_model = Controller::model('admin');
            $data = $admin_model->isLogin();
            
            if(empty($data) || EC_OK != $data['code']){
                Log::error('isLogin data is empyty or code is err . data=' . json_encode($data) );
                return false;
            }
            
            $loginUser = $data['data'];
            if(empty($loginUser)){
                Log::error('isLogin . data[loginUser] is null .');
                return false;
            }
            AdminController::setLoginSession($loginUser);
            return true;
        } catch (Exception $e) {
            Log::error('isLogin . e=' . $e->getMessage());
            return false;
        }
    }
    
    protected function loginOut(){
        Log::notice("admin loginOut str .");
        try{
            $admin_model = $this->model('admin');
            $admin_model->loginOut();
            
            $session = Controller::instance('session');
            $session_id = $session->get_id();
            
            $redis = Controller::instance('db_redis');
            $redis->delete($session_id);
            
            $session->delete('loginUser');
            $session->clear();
        } catch (Exception $e) {
            Log::error('loginOut . e=' . $e->getMessage());
        }
        Log::notice("admin loginOut end .");
        EC::success(EC_OK);
    }
    
    private function login(){
        $account	=	Request::post('account');
        $password	=	Request::post('password');
        $pincode	=	Request::post('pincode');
        $login_csrf	=	Request::post('login_csrf');
        $other_csrf	=	Request::post('other_csrf');
        
        $admin_model = $this->model('admin');
        $data = $admin_model->login(array('account' => $account,'password' => $password));
        
        if(EC_OK != $data['code']){
           Log::error('login failed !');
           EC::fail($data['code']);
        }
        
        Log::notice('login completed . data=##' . json_encode($data) . '##');
        $loginUser = $data['data'];
        AdminController::setLoginSession($loginUser);
        EC::success(EC_OK);
    }
    
    protected function changePwd(){
        $old_pwd = $this->post('old_pwd');
        $new_pwd = $this->post('new_pwd');
        if ( !$old_pwd || !$new_pwd ) {
            Log::error('change password params error!');
            EC::fail(EC_PAR_ERR);
        }
         
        $admin_model = $this->model('admin');
        $params = array();
        $params['new_pwd'] = $new_pwd;
        $params['old_pwd'] = $old_pwd;
         
        $session = Controller::instance('session');
        $id = $session->get('loginUser')['id'];
        $params['id'] = $id;
         
        $data = $admin_model->changePwd( $params);
        if(EC_OK != $data['code']){
            Log::error('change password Fail!');
            EC::fail($data['code']);
        }
    
        EC::success(EC_OK);
    }
    
}