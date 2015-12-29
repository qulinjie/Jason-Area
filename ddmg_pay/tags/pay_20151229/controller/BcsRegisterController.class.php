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
                case 'getInfo':
                    $this->getInfo();
                    break;
                case 'changeStatus':
                    $this->changeStatus();
                    break;
                case 'create':
                    $this->create();
                    break;
                case 'exportData':
                    $this->exportData();
                    break;
                case 'registerAccount':
                    $this->registerAccount();
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
        $order_no = Request::post('order_no');
        $code = Request::post('code');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $type = Request::post('type');
        $order_status = Request::post('order_status');
        $order_time1 = Request::post('order_time1');
        $order_time2 = Request::post('order_time2');
        $seller_name = Request::post('seller_name');
        $seller_conn_name = Request::post('seller_conn_name');
        $order_sum_amount1 = Request::post('order_sum_amount1');
        $order_sum_amount2 = Request::post('order_sum_amount2');
    
        $bcsRegister_model = $this->model('bcsRegister');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'order_no', 'user_id', 'code', 'time1', 'time2', 'type', 'order_status',
                    'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2' ] as $val){
            if($$val) $params[$val] = $$val;
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
    
        if(!$current_page || 0 >= $current_page) {
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
        $entity_list_html = $this->render('bcsRegister_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('bcsRegister', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'bcsRegister', 'bcsRegister_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
    protected function exportData() {
        $export_type = Request::post('export_type'); // 导出数据 1-当前页 2-全部
        $current_page = Request::post('page');
        $order_no = Request::post('order_no');
        $code = Request::post('code');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $type = Request::post('type');
        $order_status = Request::post('order_status');
        $order_time1 = Request::post('order_time1');
        $order_time2 = Request::post('order_time2');
        $seller_name = Request::post('seller_name');
        $seller_conn_name = Request::post('seller_conn_name');
        $order_sum_amount1 = Request::post('order_sum_amount1');
        $order_sum_amount2 = Request::post('order_sum_amount2');
    
        if(!$export_type){
            Log::error('export params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        $bcsRegister_model = $this->model('bcsRegister');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'order_no', 'user_id', 'code', 'time1', 'time2', 'type', 'order_status',
            'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        if(BcsRegisterModel::$_export_type_page == $export_type) {
            $data_cnt = $bcsRegister_model->searchCnt($params);
            if(EC_OK != $data_cnt['code']){
                Log::error("searchCnt failed . ");
                EC::fail($data_cnt['code']);
            }
        
            $cnt = $data_cnt['data'];
        
            $conf = $this->getConfig('conf');
            $page_cnt = $conf['page_count_default'];
        
            $total_page = ($cnt % $page_cnt) ? (integer)($cnt / $page_cnt) + 1 : $cnt / $page_cnt;
        
            if(!$current_page || 0 >= $current_page) {
                $current_page = 1;
            } if($current_page > $total_page) {
                $current_page = $total_page;
            }
        
            $params['current_page'] = $current_page;
            $params['page_count'] = $page_cnt;
        }
        $data = $bcsRegister_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_list = $data['data'];
        
        $excel_name = "我的大大付款_" . date('Ymd_His',time());
        $excel = $this->instance('excel');
        $excel->setMenu(array('序号', '订单号'));
        $content = array();
        foreach ( $data_list as $key=>$rows ){
            $content[$key] = array(($key+1+($current_page-1)*$page_cnt), $rows['order_no']);
        }
        $excel->setData($content);
        $excel->download($excel_name);
        exit(0);
    }
    
    private function changeStatus(){
        $id = Request::post('id');
        $order_status = Request::post('order_status');
    
        Log::notice('changeStatus-Request ==== >>> id=' . $id . ',order_status=' . $order_status);
        if(!$id){
            Log::error('changeStatus params error!');
            EC::fail(EC_PAR_ERR);
        }
    
        $bcsRegister_model = $this->model('bcsRegister');
        $user_id = self::getCurrentUserId();
    
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
    
        if(empty($params)){
            Log::error('update params is empty!');
            EC::fail(EC_PAR_BAD);
        }
    
        $data_old = $bcsRegister_model->getInfo($params);
        if(EC_OK != $data_old['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_old['code']);
        }
        $data_obj = $data_old['data'][0];
        if(empty($data_obj)) {
            Log::error('getInfo empty !');
            EC::fail(EC_RED_EMP);
        }
//         if( BcsRegisterModel::$_is_delete_true == $data_obj['is_delete'] ) {
//             Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
//             EC::fail(EC_RED_EXP);
//         }
//         if( BcsRegisterModel::$_status_waiting != $data_obj['order_status'] ) {
//             Log::error('record status is exception . status=' . $data_obj['order_status']);
//             EC::fail(EC_RED_EXP);
//         }
    
//         $params['order_status'] = BcsRegisterModel::$_status_refuse;
        $params['disenabled_timestamp'] = date('Y-m-d H:i:s',time());
    
        Log::notice('changeStatus ==== >>> params=' . json_encode($params) );
        $data = $bcsRegister_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
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
        $bcsRegister_html = $this->render('bcsRegister',[],true);
        $this->render('index',['page_type'=>'bcsRegister','bcsRegister_html'=>$bcsRegister_html]);
    }
    
    private function registerAccount(){
        $params = [
            'MCH_NO'               => self::getConfig('conf')['MCH_NO'],// 商户编号
            'SIT_NO'               => $this->model('id')->getSitNo(),   // 席位号
            'CUST_CERT_TYPE'       => $this->post('certType'),          // 客户证件类型
            'CUST_CERT_NO'         => $this->post('certNo'),            // 客户证件号码
            'CUST_NAME'            => $this->post('custName'),          // 客户名称
            'CUST_ACCT_NAME'       => $this->post('custAcctName'),      // 客户账户名
            'CUST_SPE_ACCT_NO'     => $this->post('custSpeAcctNo'),     // 客户结算账户
            'CUST_SPE_ACCT_BKTYPE' => $this->post('custAcctBkType'),    // 客户结算账户行别
            'CUST_SPE_ACCT_BKID'   => '',	                            // 客户结算账户行号
            'CUST_SPE_ACCT_BKNAME' => '',	                            // 客户结算账户行名
            'ENABLE_ECDS'          => $this->post('enableEcds'),        // 是否开通电票
            'IS_PERSON'            => $this->post('isPerson'),          // 是否个人
            'CUST_PHONE_NUM'       => $this->post('custPhoneNum'),      // 客户手机号码
            'CUST_TELE_NUM'        => $this->post('custTeleNum'),       // 客户电话号码
            'CUST_ADDR'            => $this->post('custAddress'),       // 客户地址
            'RMRK'                 => $this->post('custMark')           // 备注
        ];

        //先写数据库
        $response = $this->model('bcsRegister')->create($params);
        if($response['code'] !== EC_OK){
            Log::error('registerAccount insert db error code '.$response['code']);
            EC::fail($response['code']);
        }

        $data = $this->model('bank')->registerCustomer($params);
        
        $conf = $this->getConfig('conf');
        $CSBankSoapUrl = $conf['CSBankSoapUrl'];
        $params = array();
        $params['wsdlUrl'] = strval($CSBankSoapUrl);
        $params['xml'] = strval($data[0]);
        Log::notice('params==createByJava--------------------->>' . var_export($params, true));
        $data = $this->model('bcsRegister')->createByJava($params);
        
        Log::notice("bank register return data >>>>>".json_encode($data));
        if($data['code'] !==0){
            Log::error('create Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }

    private function yang_gbk2utf8($str){
        $charset = mb_detect_encoding($str,array('UTF-8','GBK','GB2312'));
        $charset = strtolower($charset);
        if('cp936' == $charset){
            $charset='GBK';
        }
        if("utf-8" != $charset){
            $str = iconv($charset,"UTF-8//IGNORE",$str);
        }
        return $str;
    }
}