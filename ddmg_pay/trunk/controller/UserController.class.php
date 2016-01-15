<?php


class UserController extends BaseController
{
    public function handle($params = array())
    {
        switch ($params[0]) {
            case 'getIndex':
                $this->searchList(true);
                break;
            case 'searchList':
                $this->searchList();
                break;
            case 'searchListAll':
                $this->searchListAll();
                break;
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

    protected function searchList($isIndex = false) {
        $current_page = Request::post('page');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $status = Request::post('status');
        $account = Request::post('account');
        $nicename = Request::post('nicename');
    
        $user_model = $this->model('user');
        //$user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'status', 'nicename', 'account', 'time1', 'time2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        $data_cnt = $user_model->searchCnt($params);
        if(EC_OK != $data_cnt['code']){
            Log::error("searchCnt failed . ");
            EC::fail($data_cnt['code']);
        }
    
        $cnt = $data_cnt['data'];
    
        $conf = $this->getConfig('conf');
        $page_cnt = $conf['page_count_default'];
    
        $total_page = ($cnt % $page_cnt) ? (integer)($cnt / $page_cnt) + 1 : $cnt / $page_cnt;
    
        if(empty($current_page) || 0 >= $current_page) {
            $current_page = 1;
        } if($current_page > $total_page) {
            $current_page = $total_page;
        }
    
        $params['current_page'] = $current_page;
        $params['page_count'] = $page_cnt;
        $data = $user_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_list = $data['data'];
    
//         if(!empty($data_list)) {
//             $cert_type = $this->getConfig('certificate_type');
//             foreach ($data_list as $key => $item){
//                 $data_list[$key]['CUST_CERT_TYPE'] = $cert_type[$item['CUST_CERT_TYPE']];
//             }
//         }
    
        $entity_list_html = $this->render('user_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('user', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'user', 'user_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
    protected function searchListAll() {
        $current_page = Request::post('page');
        $status = Request::post('status');
    
        $user_model = $this->model('user');
        $params  = array();
        foreach ([ 'status', 'nicename', 'account', 'time1', 'time2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        $data = $user_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
        $data_list = $data['data'];
        EC::success(EC_OK, array('data' => $data_list));
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

    public static function isAdmin()
    {
        $data = self::model('user')->isAdmin();
        if($data['code'] !== EC_OK){
            Log::error('UserController isAdmin error code:'.$data['code']);
            EC::fail($data['code']);
        }

        return $data['data']['status'];
    }
}