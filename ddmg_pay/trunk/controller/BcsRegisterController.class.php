<?php
/**
 * @author zhangkui
 *
 */
class BcsRegisterController extends BaseController {

    public function handle($params = array()) {
        //Log::notice('BcsRegisterController  ==== >>> params=' . json_encode($params));
        if (empty($params)) {
            Log::error('Controller . params is empty . ');
            EC::fail(EC_MTD_NON);
        } else {
            switch ($params[0]) {
                case 'getIndex':
                    $this->searchList(true);
                    break;
                case 'searchList':
                    $this->searchList();
                    break;
                case 'info':
                    $this->getInfo();
                    break;                      
                case 'create':
                    $this->create();
                    break;
                case 'update':
                    $this->update();
                    break;
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }
    
    protected function searchList($isIndex = false) {
        $current_page = Request::post('page');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $status = Request::post('status');
        $SIT_NO = Request::post('SIT_NO');
        $ACCOUNT_NO = Request::post('ACCOUNT_NO');
        $account = Request::post('account');
        
        $bcsRegister_model = $this->model('bcsRegister');
        $user_model = $this->model('user');
        
        if ($account && ! empty($account)) {
            $user_data = $user_model->searchList(array(
                'account' => $account
            ));
            if (EC_OK != $user_data['code']) {
                Log::error("searchList failed . ");
            } else {
                $user_data = $user_data['data'];
                if (empty($user_data)) {
                    Log::notice("searchList is empty . ");
                    EC::success(EC_OK, array(
                        'entity_list_html' => $this->render('bcsRegister_list', array(), true)
                    ));
                } else {
                    foreach ($user_data as $key => $val) {
                        $user_id_list[] = $user_data[$key]['id'];
                    }
                }
            }
        }
        
        $params = array();
        foreach ([
            'status',
            'SIT_NO',
            'ACCOUNT_NO',
            'time1',
            'time2'
        ] as $val) {
            if ($$val)
                $params[$val] = $$val;
        }
        
        if ($user_id_list && ! empty($user_id_list)) {
            $params['user_id_list'] = $user_id_list;
        }
        
        $data_cnt = $bcsRegister_model->searchCnt($params);
        if (EC_OK != $data_cnt['code']) {
            Log::error("searchCnt failed . ");
            EC::fail($data_cnt['code']);
        }
        
        $cnt = $data_cnt['data'];
        
        $conf = $this->getConfig('conf');
        $page_cnt = $conf['page_count_default'];
        
        $total_page = ($cnt % $page_cnt) ? (integer) ($cnt / $page_cnt) + 1 : $cnt / $page_cnt;
        
        if (empty($current_page) || 0 >= $current_page) {
            $current_page = 1;
        }
        if ($current_page > $total_page) {
            $current_page = $total_page;
        }
        
        $params['current_page'] = $current_page;
        $params['page_count'] = $page_cnt;
        $data = $bcsRegister_model->searchList($params);
        if (EC_OK != $data['code']) {
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
        
        $data_list = $data['data'];
        
        if (! empty($data_list)) {
            $cert_type = $this->getConfig('certificate_type');
            foreach ($data_list as $key => $item) {
                $data_list[$key]['CUST_CERT_TYPE'] = $cert_type[$item['CUST_CERT_TYPE']];
            }
        }
        
        // 用户账号 用户名称 用户公司名称
        if (! empty($data_list)) {
            $user_id_list = array();
            foreach ($data_list as $key => $val) {
                if (! in_array($data_list[$key]['user_id'], $user_id_list, true)) {
                    $user_id_list[] = $data_list[$key]['user_id'];
                }
            }
            
            $user_data = $user_model->searchList(array(
                'user_id_list' => $user_id_list
            ));
            if (EC_OK != $user_data['code']) {
                Log::error("searchList failed . ");
            } else {
                $user_data = $user_data['data'];
                foreach ($data_list as $key1 => $val1) {
                    foreach ($user_data as $key2 => $val2) {
                        if ($data_list[$key1]['user_id'] == $user_data[$key2]['id']) {
                            $data_list[$key1]['account'] = $user_data[$key2]['account'];
                            $data_list[$key1]['nicename'] = $user_data[$key2]['nicename'];
                            $data_list[$key1]['company_name'] = $user_data[$key2]['company_name'];
                            break;
                        }
                    }
                }
            }
        }
        
        $entity_list_html = $this->render('bcsRegister_list', array(
            'data_list' => $data_list,
            'current_page' => $current_page,
            'total_page' => $total_page
        ), true);
        if ($isIndex) {
            $view_html = $this->render('bcsRegister', array(
                'entity_list_html' => $entity_list_html
            ), true);
            $this->render('index', array(
                'page_type' => 'bcsRegister',
                'bcsRegister_html' => $view_html
            ));
        } else {
            EC::success(EC_OK, array(
                'entity_list_html' => $entity_list_html
            ));
        }
    }

    private function getInfo()
    {
        if (! $id = Request::post('id')) {
            Log::error('bcsRegister getInfo id miss');
            EC::fail(EC_PAR_BAD);
        }
        
        $bcsRegister_model = $this->model('bcsRegister');
        $user_model = $this->model('user');
        $params = array(
            'id' => $id,
            'status' => 2,
            'is_delete' => 1,
            'fields' => array(
                'id',
                'user_id',
                'MCH_NO',
                'ACCOUNT_NO',
                'CUST_CERT_TYPE',
                'CUST_CERT_NO',
                'SIT_NO',
                'CUST_NAME',
                'CUST_ACCT_NAME',
                'CUST_SPE_ACCT_NO',
                'CUST_SPE_ACCT_BKTYPE',
                'CUST_SPE_ACCT_BKID',
                'CUST_SPE_ACCT_BKNAME',
                'CUST_PHONE_NUM',
                'CUST_TELE_NUM',
                'CUST_ADDR',
                'RMRK',
                'ENABLE_ECDS',
                'IS_PERSON',
                'status',
                'comment'
            )
        );
        
        $data = $bcsRegister_model->getList($params);
        if (EC_OK != $data['code']) {
            EC::fail($data['code']);
        }
        
        $data = $data['data'][0];
        if (empty($data)) {
            Log::error('Register user not exist. id=' . $id);
            EC::fail(EC_PAR_ERR);
        }
        
        $params = array(
            'id'        => $data['user_id'],
            'status'    => 1,
            'is_delete' => 1,
            'fields' => array(
                'account',
                'nicename',
                'company_name'             
        ));
        $user_data = $user_model->getList($params);
        if(EC_OK != $user_data['code']){
            Log::error("searchList failed . ");
            EC::fail(EC_PAR_ERR);
        }
        
        Log::error('response_data==>>' . var_export($data, true));
        EC::success(EC_OK,array_merge($data,$user_data['data'][0]));
    }

    private function create(){
        $params = [
            'account'              => $this->post('account'),                       //登录账户
            'password'             => self::decrypt($this->post('password')),       //登录密码
            'company_name'         => $this->post('company_name'),          //公司名称
            'CUST_CERT_TYPE'       => $this->post('CUST_CERT_TYPE'),          // 客户证件类型
            'CUST_CERT_NO'         => $this->post('CUST_CERT_NO'),            // 客户证件号码
            'CUST_NAME'            => $this->post('CUST_NAME'),          // 客户名称
            'CUST_ACCT_NAME'       => $this->post('CUST_ACCT_NAME'),      // 客户账户名
            'CUST_SPE_ACCT_NO'     => $this->post('CUST_SPE_ACCT_NO'),     // 客户结算账户
            'CUST_SPE_ACCT_BKTYPE' => $this->post('CUST_SPE_ACCT_BKTYPE'),    // 客户结算账户行别
            'CUST_SPE_ACCT_BKID'   => $this->post('CUST_SPE_ACCT_BKID'),	// 客户结算账户行号
            'CUST_SPE_ACCT_BKNAME' => $this->post('CUST_SPE_ACCT_BKNAME'),	// 客户结算账户行名
            'ENABLE_ECDS'          => $this->post('ENABLE_ECDS'),        // 是否开通电票
            'IS_PERSON'            => $this->post('IS_PERSON'),          // 是否个人
            'CUST_PHONE_NUM'       => $this->post('CUST_PHONE_NUM'),      // 客户手机号码
            'CUST_TELE_NUM'        => $this->post('CUST_TELE_NUM'),       // 客户电话号码
            'CUST_ADDR'            => $this->post('CUST_ADDR'),       // 客户地址
            'RMRK'                 => $this->post('RMRK'),           // 客户备注          
            'comment'              => $this->post('comment')           //管理员备注
        ];
                    
        $filter = ['CUST_CERT_TYPE','CUST_CERT_NO','CUST_NAME','CUST_ACCT_NAME','CUST_SPE_ACCT_NO','CUST_SPE_ACCT_BKTYPE','ENABLE_ECDS','IS_PERSON','account','password','company_name'];
        if($params['CUST_SPE_ACCT_BKTYPE'] == '1'){
            $filter[] = 'CUST_SPE_ACCT_BKID';
            $filter[] = 'CUST_SPE_ACCT_BKNAME';
        }
        foreach ($filter as $filed){
            if(!isset($params[$filed]) || $params[$filed] === '' || $params[$filed] === false){
                Log::error('bcsRegister create params error filed :'.$filed);
                EC::fail(EC_PAR_ERR);
            }
        }
        
        //创建账户    
        $checkComp = $this->model('user')->getList(['company_name' => $params['company_name'] ,'fields' => ['id']]);
        $checkComp['code'] !== EC_OK && EC::fail($checkComp['code']);        
        $checkComp['data'] && EC::fail(EC_COMPANY_EST);
        
        $checkAccount = $this->model('user')->getList(['account' => $params['account'] ,'fields' => ['id']]);
        $checkAccount['code'] !== EC_OK && EC::fail($checkAccount['code']);
        $checkAccount['data'] && EC::fail(EC_ACCOUNT_EST);
        
        $data = $this->model('user')->create([
            'account'      => $params['account'],
            'password'     => $params['password'],
            'nicename'     => $params['CUST_NAME'],
            'company_name' => $params['company_name'],
            'comment'      => $params['comment'],
            'status'       => 1,
            'user_type'    => 1,
            'is_delete'    => 1,
            'pay_password' => '',
            'personal_authentication_status' => 3,
            'company_authentication_status'  => 3,
            'add_timestamp' => date('Y-m-d H:i:s')
        ]);  
        
        if($data['code'] !== EC_OK){
            Log::error('bcsRegister create user error code='.$data['code']);
            EC::fail($data['code']);
        }
        $user_id = $data['data']['id'];        
        unset($params['account'],$params['password'],$params['company_name']);      
        $params['MCH_NO']  = $this->getMCH_NO();
        $params['user_id'] = $user_id;
        $params['is_delete'] = 1;
        $params['status']   = 3;
        $params['add_timestamp'] = date('Y-m-d H:i:s');
        
        //创建开户
        $data = $this->model('bcsRegister')->create($params);
        if($data['code'] !== EC_OK){
            Log::error('bcsRegister create bcsRegister error code: '.$data['code']);
            EC::fail($data['code']);
        }
       
        $register_id = $data['data']['id'];
        $SIT_NO      = $data['data']['SIT_NO'];
        unset($params['user_id'],$params['comment'],$params['is_delete'],$params['status'],$params['add_timestamp']);       
        $params['SIT_NO'] =  $SIT_NO;
        
        $data = $this->model('bank')->registerCustomer($params);
       
        //临时方案 开始
        $conf = $this->getConfig('conf');
        $CSBankSoapUrl = $conf['CSBankSoapUrl'];
        $params = array();
        $params['wsdlUrl'] = strval($CSBankSoapUrl);
        $params['xml'] = strval($data[0]);
        Log::notice('params==createByJava--------------------->>' . var_export($params, true));
        $data = $this->model('bcsRegister')->createByJava($params);
     
        
        if($data['error'] !== 0){
            Log::error('bcsRegister createByJava Fail!');
            $this->model('bcsRegister')->update(['id' => $register_id,'status' => 2]);
            EC::fail($data['code']);
        }
               
        if(($ACCOUNT_NO = strstr($data['data'],'<ACCOUNT_NO>')) === false){
            Log::error('bcsRegister create bank return ACCOUNT_NO is empty');
            $this->model('bcsRegister')->update(['id' => $register_id,'status' => 2]);
            EC::fail(EC_OTH);
        }      
        //临时方案结束
        
        $ACCOUNT_NO = substr($ACCOUNT_NO,strlen('<ACCOUNT_NO>'),23);
        $data = $this->model('bcsRegister')->update(['ACCOUNT_NO' => $ACCOUNT_NO,'id' => $register_id]);
        if($data['code'] !== EC_OK){
            Log::error('bcsRegister create update  ACCOUNT_NO error');
            EC::fail(EC_UPD_REC);
        }
        
        EC::success(EC_OK);
     }

    private function update(){      
         $filter = [
             'id',
             'user_id',
             'company_name',
             'account',
             'MCH_NO',  
             'SIT_NO', 
             'CUST_CERT_TYPE',
             'CUST_CERT_NO',
             'CUST_NAME',
             'CUST_ACCT_NAME',
             'CUST_SPE_ACCT_NO',
             'CUST_SPE_ACCT_BKTYPE',
             'ENABLE_ECDS',             
             'IS_PERSON'             
         ];   
         
         $params = [
             'MCH_NO'               => $this->getMCH_NO(),                   //商户编号
             'SIT_NO'               => $this->post('SIT_NO'),                //席位号
             'CUST_CERT_TYPE'       => $this->post('CUST_CERT_TYPE'),          // 客户证件类型
             'CUST_CERT_NO'         => $this->post('CUST_CERT_NO'),            // 客户证件号码
             'CUST_NAME'            => $this->post('CUST_NAME'),          // 客户名称
             'CUST_ACCT_NAME'       => $this->post('CUST_ACCT_NAME'),      // 客户账户名
             'CUST_SPE_ACCT_NO'     => $this->post('CUST_SPE_ACCT_NO'),     // 客户结算账户
             'CUST_SPE_ACCT_BKTYPE' => $this->post('CUST_SPE_ACCT_BKTYPE'),    // 客户结算账户行别
             'CUST_SPE_ACCT_BKID'   => $this->post('CUST_SPE_ACCT_BKID'),	// 客户结算账户行号
             'CUST_SPE_ACCT_BKNAME' => $this->post('CUST_SPE_ACCT_BKNAME'),	// 客户结算账户行名
             'ENABLE_ECDS'          => $this->post('ENABLE_ECDS'),        // 是否开通电票
             'IS_PERSON'            => $this->post('IS_PERSON'),          // 是否个人
             'CUST_PHONE_NUM'       => $this->post('CUST_PHONE_NUM'),      // 客户手机号码
             'CUST_TELE_NUM'        => $this->post('CUST_TELE_NUM'),       // 客户电话号码
             'CUST_ADDR'            => $this->post('CUST_ADDR'),       // 客户地址
             'RMRK'                 => $this->post('RMRK'),           // 客户备注
             'id'                   => $this->post('id'),
             'user_id'              => $this->post('user_id'),
             'account'              => $this->post('account'),
             'company_name'         => $this->post('company_name'),
             'comment'              => $this->post('comment'),
             'password'             => self::decrypt($this->post('password'))
         ];
         
         if($params['CUST_SPE_ACCT_BKTYPE'] == '1'){
             $filter[] = 'CUST_SPE_ACCT_BKID';
             $filter[] = 'CUST_SPE_ACCT_BKNAME';
         }
         
         foreach ($filter as $filed){
             if(!isset($params[$filed]) || $params[$filed] === ''){
                 Log::error('bcsRegister update params error filed :'.$filed);
                 EC::fail(EC_PAR_ERR);
             }
         }
         
         //检查帐号       
         $check = $this->model('user')->getList(['account' => $params['account'] ,'fields' => ['id']]);
         $check['code'] !== EC_OK && EC::fail($check['code']);
         $check['data'] && $check['data'][0]['id'] != $params['user_id'] && EC::fail(EC_ACCOUNT_EST);     
         
         //检查公司名称         
         $check = $this->model('user')->getList(['company_name' => $params['company_name'] ,'fields' => ['id']]);
         $check['code'] !== EC_OK && EC::fail($check['code']);
         $check['data'] && $check['data'][0]['id'] != $params['user_id'] && EC::fail(EC_COMPANY_EST);
        
         $user = ['id' => $params['user_id'], 'account' => $params['account'],'company_name' => $params['company_name'],'nicename' => $params['CUST_NAME'],'comment' => $params['comment']];
         $params['password'] && $user['password'] = md5($params['id'].$params['password']);
         
         $data = $this->model('user')->update($user);
         $data['code'] !== EC_OK && EC::fail($data['code']);
         
         unset($params['account'],$params['company_name'],$params['password']);
         $data = $this->model('bcsRegister')->update($params);
         $data['code'] !== EC_OK && EC::fail($data['code']);         
         
         //注册到银行
         $bcsRegister_id = $params['id'];
         unset($params['id'],$params['user_id'],$params['comment']);       
         $data = $this->model('bank')->registerCustomer($params);
    
         //临时方案 开始
         $conf = $this->getConfig('conf');
         $CSBankSoapUrl = $conf['CSBankSoapUrl'];
         $params = array();
         $params['wsdlUrl'] = strval($CSBankSoapUrl);
         $params['xml'] = strval($data[0]);
         Log::notice('params==createByJava--------------------->>' . var_export($params, true));
         $data = $this->model('bcsRegister')->createByJava($params);          
     
         if($data['error'] !== 0){
             Log::error('bcsRegister createByJava Fail!');
             $this->model('bcsRegister')->update(['id' => $bcsRegister_id,'status' => 2]);
             EC::fail($data['code']);
         }
          
         if(($ACCOUNT_NO = strstr($data['data'],'<ACCOUNT_NO>')) === false){
             Log::error('bcsRegister create bank return ACCOUNT_NO is empty');
             $this->model('bcsRegister')->update(['id' => $bcsRegister_id,'status' => 2]);
             EC::fail(EC_OTH);
         }
         //临时方案结束
     
         $ACCOUNT_NO = substr($ACCOUNT_NO,strlen('<ACCOUNT_NO>'),23);
         $data = $this->model('bcsRegister')->update(['ACCOUNT_NO' => $ACCOUNT_NO,'status' => 3,'id' => $bcsRegister_id]);
         if($data['code'] !== EC_OK){
             Log::error('bcsRegister create update  ACCOUNT_NO error');
             EC::fail(EC_UPD_REC);
         }
     
         EC::success(EC_OK);
     }
}