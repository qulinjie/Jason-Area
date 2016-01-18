<?php
/**
 * @author zhangkui
 *
 */
class BcsRegisterController extends BaseController {

    public function handle($params = array()) {
        Log::notice('BcsRegisterController  ==== >>> params=' . json_encode($params));
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
                    $this->getInfoById();
                    break;
                case 'getInfo':
                    $this->getInfo();
                    break;           
                case 'create':
                    $this->create();
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
        
        if($account && !empty($account)){
            $user_data = $user_model->searchList(array('account' => $account));
            if(EC_OK != $user_data['code']){
                Log::error("searchList failed . ");
            } else {
                $user_data = $user_data['data'];
                if(empty($user_data)){
                    Log::notice("searchList is empty . ");
                    EC::success(EC_OK, array('entity_list_html' => $this->render('bcsRegister_list', array(), true) ));
                } else {
                    foreach ($user_data as $key => $val){
                        $user_id_list[] = $user_data[$key]['id'];
                    }
                }
            }
        }
    
        $params  = array();
        foreach ([ 'status', 'SIT_NO', 'ACCOUNT_NO', 'time1', 'time2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        if( $user_id_list && !empty($user_id_list) ){
            $params['user_id_list'] = $user_id_list;
        }
        
        $data_cnt = $bcsRegister_model->searchCnt($params);
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
        $data = $bcsRegister_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_list = $data['data'];
        
        if(!empty($data_list)) {
            $cert_type = $this->getConfig('certificate_type');
            foreach ($data_list as $key => $item){
                $data_list[$key]['CUST_CERT_TYPE'] = $cert_type[$item['CUST_CERT_TYPE']];
            }
        }
        
        // 用户账号 	用户名称 	用户公司名称
        if(!empty($data_list)){
            $user_id_list = array();
            foreach($data_list as $key => $val){
                if( !in_array($data_list[$key]['user_id'], $user_id_list, true)){
                    $user_id_list[] = $data_list[$key]['user_id'];
                }
            }
        
            $user_data = $user_model->searchList(array('user_id_list' => $user_id_list));
            if(EC_OK != $user_data['code']){
                Log::error("searchList failed . ");
            } else {
                $user_data = $user_data['data'];
                foreach($data_list as $key1 => $val1){
                    foreach($user_data as $key2 => $val2){
                        if( $data_list[$key1]['user_id'] == $user_data[$key2]['id'] ){
                            $data_list[$key1]['account'] = $user_data[$key2]['account'];
                            $data_list[$key1]['nicename'] = $user_data[$key2]['nicename'];
                            $data_list[$key1]['company_name'] = $user_data[$key2]['company_name'];
                            break ;
                        }
                    }
                }
            }
        }
        
        $entity_list_html = $this->render('bcsRegister_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('bcsRegister', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'bcsRegister', 'bcsRegister_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
    private function getInfoById(){
        $id	=	Request::post('id');
        
        $bcsRegister_model = $this->model('bcsRegister');
        $user_model = $this->model('user');
        
        $data = $bcsRegister_model->getInfo(array('id' => $id));
        if(EC_OK != $data['code']){
            EC::fail($data['code']);
        }
        $data = $data['data'];
        if(empty($data)){
            Log::error('Register user not exist. id=' . $id);
            EC::fail(EC_PAR_ERR);
        }
        
        $user_data = $user_model->searchList(array('id' => $data[id]));
        if(EC_OK != $user_data['code']){
            Log::error("searchList failed . ");
        } else {
            $user_data = $user_data['data'][0];
            $data[0]['account'] = $user_data['account'];
            $data[0]['nicename'] = $user_data['nicename'];
            $data[0]['company_name'] = $user_data['company_name'];
        }
        
        Log::error('response_data==>>' . var_export($data, true));
        EC::success(EC_OK,$data);
    }
    
    protected function getInfo() {
        $bcsRegister_model = $this->model('bcsRegister');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        $params['user_id'] = $user_id;
    
        $data = $bcsRegister_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
    
        $data_info = $data['data'][0];
        $view_html = $this->render('bcsRegisterInfo', array('item' => $data_info), true);
        $this->render('index', array('page_type' => 'bcsRegister', 'bcsRegister_html' => $view_html));
    }

    private function create(){
        $params = [
            //'MCH_NO'               => self::getConfig('conf')['MCH_NO'],    // 商户编号
            //'SIT_NO'               => $this->post('SIT_NO'),                // 客户证件类型
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
            'user_id'              => $this->post('user_id'),          //用户ID
            'comment'              => $this->post('comment')           //管理员备注
        ];
                    
        $filter = ['CUST_CERT_TYPE','CUST_CERT_NO','CUST_NAME','CUST_ACCT_NAME','CUST_SPE_ACCT_NO','CUST_SPE_ACCT_BKTYPE','ENABLE_ECDS','IS_PERSON','user_id'];
        foreach ($filter as $filed){
            if(!isset($params[$filed]) || !$params[$filed]){
                Log::error('bcsRegister create params error filed :'.$filed);
                EC::fail(EC_PAR_ERR);
            }
        }     
             
        $params['MCH_NO'] = $this->getMCH_NO();
        
        //先写数据库
        $data = $this->model('bcsRegister')->create($params);
        if($data['code'] !== EC_OK){
            Log::error('bcsRegister create error code: '.$data['code']);
            EC::fail($data['code']);
        }
        
        unset($params['user_id'],$params['comment']);       
        $params['SIT_NO'] = $data['data']['SIT_NO'];
        $data = $this->model('bank')->registerCustomer($params);
       
        //临时方案 开始
        $conf = $this->getConfig('conf');
        $CSBankSoapUrl = $conf['CSBankSoapUrl'];
        $params = array();
        $params['wsdlUrl'] = strval($CSBankSoapUrl);
        $params['xml'] = strval($data[0]);
        Log::notice('params==createByJava--------------------->>' . var_export($params, true));
        $data = $this->model('bcsRegister')->createByJava($params);
     
        if($data['error'] !==0){
            Log::error('bcsRegister createByJava Fail!');
            EC::fail($data['code']);
        }
               
        if(($ACCOUNT_NO = strstr($data['data'],'<ACCOUNT_NO>')) === false){
            Log::error('bcsRegister create bank return ACCOUNT_NO is empty');
            EC::fail(EC_OTH);
        }      
        //临时方案结束
        
        $ACCOUNT_NO = substr($ACCOUNT_NO,strlen('<ACCOUNT_NO>'),23);
        $data = $this->model('bcsRegister')->update(['ACCOUNT_NO' => $ACCOUNT_NO,'SIT_NO' => $params['SIT_NO']]);
        if($data['code'] !== EC_OK){
            Log::error('bcsRegister create update  ACCOUNT_NO error');
            EC::fail(EC_UPD_REC);
        }
        
        EC::success(EC_OK,['ACCOUNT_NO' => $ACCOUNT_NO]);
     }

}