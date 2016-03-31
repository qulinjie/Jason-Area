<?php

/**
 * 
 * @author zhangsong
 *
 */

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
            case 'update':
                $this->update();
                break;
            case 'getInfo':
                $this->getInfo();
                break;
            case 'getCert':
                $this->getCert();
                break;
                
            case 'erp_getList':
                $this->erp_getList();
                break;
            default:
                Log::error('UserController method not exists ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }
    
    private function getCert()
    {
        $id = $this->get('id');
        $flag = $this->get('flag');
        if(!$id || !$flag){
            exit('403 forbidden');
        }      
        
        $data = $this->model('cert')->getList(['user_id' => $id]);
        $data['code'] !== EC_OK && EC::fail($data['code']);
        
        $file = DOIT_ROOT;
        if($flag == '10000'){
            $file .= $data['data'][0]['certificate_filepath'];
        }else if($flag == '20000'){
            $file .= $data['data'][0]['business_license_filepath'];
        }else{
            exit('403 forbidden');
        }    
        
     
        if (file_exists($file)) {
            header('content-type:' . getimagesize($file)['mime']);
            echo file_get_contents($file);
            exit;
        }
    }

    protected function getInfo()
    {
        $params['id']        = $this->post('id');
        $params['is_delete'] = 1;
        
        if(!$params['id'] ){
            Log::error('User getInfo params error');
            EC::fail(EC_PAR_ERR);
        }
        
        $params['fields'] = array(
            'id',
            'account',
            'nicename',
            'status',
            'comment',   
            'company_name',
            'add_timestamp',
            'personal_authentication_status',
            'company_authentication_status',            
        );
        
        $data   = $this->model('user')->getList($params);
        $data['code'] !== EC_OK && EC::fail($data['code']);
        
        //用户不存在
        !$data['data'] && EC::success(EC_OK);
        $user = $data['data'];
        
        $params = array(
            'user_id'=> $params['id'],
            'fields' => array(
                //'certificate_filename',
                //'certificate_filepath',
                'legal_name',
                'business_license'
                //'business_license_filename',
                //'business_license_filepath'
        ));
       
        $data = $this->model('cert')->getList($params);
        $data['code'] !== EC_OK && EC::fail($data['code']);
           
        EC::success(EC_OK,array_merge($user[0],$data['data'][0]));        
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
        $userList = $this->model('user')->getList(array('status' => '1','is_delete' => '1','fields' => array('id,account,nicename,company_name')));
        if(EC_OK !== $userList['code']){
            Log::error("searchList failed . ");
            EC::fail($userList['code']);
        }
        
        //无用户
        !$userList['data'] && EC::success(EC_OK);
        
        $bcsRegisterList = $this->model('bcsRegister')->getList(array('fields' => array('user_id')));
        if($bcsRegisterList['code'] !== EC_OK){
            Log::error('User searchListAll error code='.$bcsRegisterList['code']);
            EC::fail($bcsRegisterList['code']);
        }
        
        //过滤已经开户的数据
        if($bcsRegisterList['data']){
            $filter = array_flip(array_column($bcsRegisterList['data'], 'user_id'));         
            foreach ($userList['data'] as $key => $val){  
                if(isset($filter[(int)$val['id']])){
                    unset($userList['data'][$key]);                   
                }
            }            
        }
       
        EC::success(EC_OK, $userList['data']);
    }
    
    private function update(){  
        $params = [
            'id'                 => $this->post('id'),
            'account'            => $this->post('user-account'),
            'real_name'          => $this->post('user-name'),
            'legal_name'         => $this->post('user-legal-name'),
            'company_name'       => $this->post('user-company-name'),
            'business_license'   => $this->post('user-business-license'),
            'status'             => $this->post('user-status'),
            'comment'            => $this->post('user-remark'),
            'personal_authentication_status' => $this->post('user-person-cert'),
            'company_authentication_status'  => $this->post('user-company-cert')        
        ];
        
        if(!$params['id']){
            Log::error('User update id is empty');
            EC::fail(EC_PAR_ERR);
        }       
        
        
        $response = $this->model('user')->update($params);
        $response['code'] !== EC_OK && EC::fail($response['code']);
        
        EC::success(EC_OK);
    }
    
    private function login_old()
    {   
        if(IS_POST){
            $tel  = $this->post('account');
            $pwd  = $this->post('password');
            $code = $this->post('pinCode');
            (!$tel || !$pwd || !$code) && EC::fail(EC_PAR_BAD);

            $pinCode = self::instance('pincode');
            !$pinCode->check($code) && EC::fail(EC_PINCODE_ERR);

            $data = $this->model('user')->login(['tel' => $tel, 'pwd' => $pwd]);
            $data['code'] !== EC_OK && EC::fail($data['code']);
            
            $session = $this->instance('session');
            $session->set('_loginUser',$data['data']);
           
            EC::success(EC_OK);
        }
        self::isLogin() && $this->redirect(Router::getBaseUrl());
        $this->render('login');
    }

    private function logout()
    {
        $response = $this->model('user')->logout();
        if($response['code'] !== EC_OK){
            Log::error('User Logout error '.$response['code']);
        }
        
        $session = $this->instance('session');
        $session->clear();
        $session->destroy();
       
        $cookie = $this->instance('cookie');
        $cookie->clear(Router::getBaseUrl());
        
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
            $oldPwd = self::decrypt($this->post('oldPwd'));
            $newPwd = self::decrypt($this->post('newPwd'));
            if(!$oldPwd || !$newPwd){
                Log::error('User passwordReset oldPwd or newPwd is empty');
                EC::fail(EC_PAR_BAD);
            }

            $loginUser = UserController::getLoginUser();         
          
            if(md5($loginUser['id'].$oldPwd) != $loginUser['password']){
                Log::error('User passwordReset error oldPwd='.$oldPwd);
                EC::fail(EC_PWD_WRN);
            }
            
            $response = $this->model('user')->update(['password' => md5($loginUser['id'].$newPwd),'id' => $loginUser['id']]);
            $response['code'] !== EC_OK && EC::fail($response['code']);
            EC::success(EC_OK);
        }

        $password_html = $this->render('loginPasswordReset',[],true);
        $this->render('index',['page_type' => 'user','password_html' =>$password_html]);
    }

    public static function isLogin()
    {
        $session = self::instance('session');
        if(!$session->is_set('_loginUser')){
            return self::getLoginUser() == true;
        }
        
        return true;
    }
    
    public static function isSeller()
    {
        $session = self::instance('session');
        if(!$session->is_set('_loginUser')){
            $session = self::getLoginUser();
        }
        if(1 == $session->get('_loginUser')['user_type'] ){
            return true;
        }
        return false;
    }
    
    public static function isFUser(){
    	$session = self::instance('session');
    	if(!$session->is_set('_loginUser')){
    		$session = self::getLoginUser();
    	}
    	if($session->get('_loginUser')['usercode'] == $session->get('_loginUser')['fuserid'] ){
    		return true;
    	}
    	return false;
    }

    //{"userid":"68ff4da6-8dc3-4a60-805a-6fbd609518b9","usercode":"110002","username":"\u674e\u56db","loginid":"110002",
    //"mobile":"18073215757","email":"hisyz@qq.com","is_buyer":1,"is_seller":1,"is_partner":1,"is_manager":1,"is_bank":1,"is_ddmg":1,
    //"is_paymanage":2,"managerid":null,"erp_czydm":"0138","erp_ygdm":"0138","erp_fgsdm":"007","erp_fgsmc":null,"erp_bmdm":"012",
    //"erp_bmmc":null,"user_id":"110002","account":"110002","name":"\u674e\u56db"}
    public static function getLoginUser()
    {
        $session = self::instance('session');
        $loginUser = !empty($session->get('loginUser')) ? $session->get('loginUser') : $session->get('_loginUser');
        if(!$loginUser){
            $data = self::model('user')->getLoginUser();
            Log::notice("getLoginUser . data = ##" . json_encode($data) . "##");
            if($data['code'] === EC_OK){
                $session->set('_loginUser',$data['data']);               
                return $data['data'];
            }else{
                Log::error('User getLoginUser not login . return empty data . ');
                return [];
            }
        }
       
        return $loginUser;
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

    
    private function login()
    {
        if(IS_POST){
            $account  = $this->post('account');
            $password  = $this->post('password');
            $code = $this->post('pinCode');
            (!$account || !$password) && EC::fail(EC_PAR_BAD);
    
//             $pinCode = self::instance('pincode');
//             !$pinCode->check($code) && EC::fail(EC_PINCODE_ERR);
    
            $data = $this->model('user')->erp_login(['loginid' => $account, 'userpwd' => $password]);
            $data['code'] !== EC_OK && EC::fail($data['code']);
    		
            //一级和二级审批人的检测
            if(empty($data['data']['managerid']) || empty($data['data']['fuserid'])){
            	Log::notice('login error . data=' . json_encode($data['data']) );
            	EC::fail("登录失败：未设置一级或二级审核人！");
            }
            
            $session = $this->instance('session');
            $session->set('_loginUser',$data['data']);
             
            EC::success(EC_OK);
        }
        self::isLogin() && $this->redirect(Router::getBaseUrl());
        $this->render('login');
    }
    
    public function erp_getList(){
        $user_model = $this->model('user');
        $params = array();
        $params['page'] = 1;
        $params['rows'] = 1000;
        $params['is_paymanage'] = '2';
        $params['is_partner'] = '1';
        $data = $user_model->erp_getList($params);
        
        if(EC_OK_ERP != $data['code'] ){
            Log::error('erp_getList failed . code=' . $data['code'] . ',msg=' . $data['msg']);
            EC::fail(EC_ERPE_FAI);
        }
        Log::notice('erp_getList success . data=' . json_encode($data['data']) );
        $data_lists_user = $data['data']['data'];
        Log::notice("response-data ==============data_lists_user=============>> data = ##" . json_encode($data_lists_user) . "##" );
        
        $bcsCustomer_model = $this->model('bcsCustomer');
        $params = array();
        $params['record_bank_type'] = 2;
        $data = $bcsCustomer_model->searchList($params, null, null);
        if(EC_OK != $data['code']){
            EC::fail($data['code']);
        }
        $data_lists_card = $data['data'];
        
        $user_id_matched = array();
        foreach ($data_lists_card as $objKey=>$objVal){
            if( '-1' != $objVal['user_id'] ){
                $user_id_matched[] = $objVal['user_id'];
            }
        }
        Log::notice("response-data ==============user_id_matched=============>> data = ##" . json_encode($user_id_matched) . "##" );
        
        if( !empty($user_id_matched) ){
            $data_lists_user_tmp = array();
            foreach ($data_lists_user as $objKey=>$objVal){
                $usercode= $objVal['usercode'];
                if(!in_array($usercode,$user_id_matched)){
                    $data_lists_user_tmp[] = $objVal;
                }
            }
            $data_lists_user = $data_lists_user_tmp;
        }
        Log::notice("response-data ==============data_lists_user=============>> data = ##" . json_encode($data_lists_user) . "##" );
        
        EC::success(EC_OK, $data_lists_user);
    }
    
}