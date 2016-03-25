<?php
/**
 * 客户信息
 * @author zhangkui
 *
 */
class BcsCustomerController extends BaseController {

    public function handle($params = array()) {
        Log::notice('BcsCustomerController  ==== >>> params=' . json_encode($params));
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
//                 case 'changeStatus':
//                     $this->changeStatus();
//                     break;
//                 case 'delete':
//                     $this->delete();
//                     break;
//                 case 'create':
//                     $this->create();
//                     break;
                case 'loadInfo':
                    $this->loadInfo();
                    break;
                case 'spd_loadAccountList':
                    $this->spd_loadAccountList();
                    break;
//                 case 'exportData':
//                     $this->exportData();
//                     break;
                case 'transfer':
                    $this->transfer();
                    break;
                case 'getInflow': //出入金交易明细
                    $this->getInflow();
                    break;
                case 'getIncomePay': //客户收付款明细查询
                    $this->getIncomePay();
                    break;
                    
                case 'getAllList':
                    $this->getAllList();
                    break;
                case 'updateBind':
                    $this->updateBind();
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
    
        $bcsCustomer_model = $this->model('bcsCustomer');
//         $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'status', 'SIT_NO', 'ACCOUNT_NO', 'time1', 'time2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        $data_cnt = $bcsCustomer_model->searchCnt($params);
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
        $data = $bcsCustomer_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_list = $data['data'];
        
        if(!empty($data_list)) {
            $cert_type = $this->getConfig('certificate_type');
            foreach ($data_list as $key => $item){
                $data_list[$key]['MBR_CERT_TYPE'] = $cert_type[$item['MBR_CERT_TYPE']];
            }
        }
        
        $entity_list_html = $this->render('bcsCustomer_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('bcsCustomer', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'bcsCustomer', 'bcsCustomer_html' => $view_html));
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
        
        $bcsCustomer_model = $this->model('bcsCustomer');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'order_no', 'user_id', 'code', 'time1', 'time2', 'type', 'order_status',
            'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        if(BcsCustomerModel::$_export_type_page == $export_type) {
            $data_cnt = $bcsCustomer_model->searchCnt($params);
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
        $data = $bcsCustomer_model->searchList($params);
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
    
        $bcsCustomer_model = $this->model('bcsCustomer');
        $user_id = self::getCurrentUserId();
    
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
    
        if(empty($params)){
            Log::error('update params is empty!');
            EC::fail(EC_PAR_BAD);
        }
    
        $data_old = $bcsCustomer_model->getInfo($params);
        if(EC_OK != $data_old['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_old['code']);
        }
        $data_obj = $data_old['data'][0];
        if(empty($data_obj)) {
            Log::error('getInfo empty !');
            EC::fail(EC_RED_EMP);
        }
//         if( BcsCustomerModel::$_is_delete_true == $data_obj['is_delete'] ) {
//             Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
//             EC::fail(EC_RED_EXP);
//         }
//         if( BcsCustomerModel::$_status_waiting != $data_obj['order_status'] ) {
//             Log::error('record status is exception . status=' . $data_obj['order_status']);
//             EC::fail(EC_RED_EXP);
//         }
    
//         $params['order_status'] = BcsCustomerModel::$_status_refuse;
        $params['disenabled_timestamp'] = date('Y-m-d H:i:s',time());
    
        Log::notice('changeStatus ==== >>> params=' . json_encode($params) );
        $data = $bcsCustomer_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }
    
    protected function getInfo() {
        $bcsCustomer_model = $this->model('bcsCustomer');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        $params['user_id'] = $user_id;
    
        $data = $bcsCustomer_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
    
        $data_info = $data['data'][0];
        
        $cert_type = $this->getConfig('certificate_type');
        $data_info['MBR_CERT_TYPE'] = $cert_type[$data_info['MBR_CERT_TYPE']];
        
        $view_html = $this->render('bcsCustomerInfo', array('item' => $data_info), true);
        $this->render('index', array('page_type' => 'bcsCustomer', 'bcsCustomer_html' => $view_html));
    }
    
    private function create(){
        $post_data = self::getPostDataJson();
        if(empty($post_data)) {
            Log::error('post_data params error!');
            EC::fail(EC_PAR_ERR);
        }
        // request 数据
        $request_data = $post_data['data'];
        
        $code = $request_data['code']; // 授权码
        $seller_id = $request_data['seller_id']; // 卖家ID
        $seller_name = $request_data['company']; // 卖家(公司)名称
        $seller_conn_name = $request_data['seller_name']; // 联系人
        $seller_tel = $request_data['tel']; // 联系人电话
        $seller_comp_phone = $request_data['seller_comp_phone']; // 公司电话
        $order_no = $request_data['order_num']; // 订单号
        $order_timestamp = $request_data['order_timestamp']; // 订单时间/日期
        $order_goods_name = $request_data['product_name']; // 品名
        $order_goods_size = $request_data['size_name']; // 规格
        $order_goods_type = $request_data['material_name']; // 材质
        $order_goods_price = $request_data['price']; // 单价（元/ 吨）
        $order_goods_count = $request_data['ton']; // 订购量（吨）
        $order_delivery_addr = $request_data['delivery_address']; // 交货地
        $order_sum_amount = $request_data['amount']; // 订单金额（元）
    
        if(!$code || !$seller_name || !$order_no || !$order_sum_amount ){
            Log::error('create params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        $code_model = $this->model('authorizationCode');
        $data_code = $code_model->getInfo(array('code' => $code));
        if(EC_OK != $data_code['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_code['code']);
        }
        $code_obj = $data_code['data'][0];
        if(empty($code_obj)) {
            Log::error('getInfo code is not exists ! id=' . $code_obj['id']);
            EC::fail(EC_CODE_ERR);
        }
        if( AuthorizationCodeModel::$_is_delete_true == $code_obj['is_delete'] 
            || !AuthorizationCodeModel::$_status_enabled == $code_obj['status'] 
            || !$code_model->validataionCodeActive($code_obj) 
            ){
            Log::error('code validation failed ! id=' . $code_obj['id'] . ',code=' . $code_obj['code'] );
            EC::fail(EC_CODE_ERR);
        }
    
        $params = array();
        $params['user_id'] = $code_obj['user_id']; // 授权码 所属用户ID
        $params['code_id'] = $code_obj['id']; // 授权码 ID
        $params['code_used_count'] = $code_obj['used_count']; // 授权码 已经使用次数
        $params['order_status'] = BcsCustomerModel::$_status_waiting; // 订单交易状态 1-待付款
        $params['pay_timestamp'] = BcsCustomerModel::$_empyt_time; // 操作（付款/拒付）时间
        foreach ([ 'code', 'seller_id', 'seller_name', 'seller_conn_name', 'seller_tel', 'seller_comp_phone',
                    'order_no', 'order_timestamp', 'order_goods_name', 'order_goods_size', 'order_goods_type', 'order_goods_price', 'order_goods_count',
                    'order_delivery_addr', 'order_sum_amount' ] as $val ){
            if($$val) $params[$val] = $$val;
        }
    
        if(empty($params)){
            Log::error('create params is empty!');
            EC::fail(EC_PAR_BAD);
        }
        
        $bcsCustomer_model = $this->model('bcsCustomer');
        $data = $bcsCustomer_model->create($params);
        if(EC_OK != $data['code']){
            Log::error('create Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }
    
    protected function loadInfo() {
        $bcsBank_model = $this->model('bank');
        $bcsCustomer_model = $this->model('bcsCustomer');
        $bcsRegister_model = $this->model('bcsRegister');
        $conf = $this->getConfig('conf');
        
        
        $user_id = '';
        if(AdminController::isAdmin()) {
            $user_id = Request::post('user_id');
        } else {
            $user_id = self::getCurrentUserId();
        }
        $mch_no = $conf['MCH_NO']; // 商户编号
        
        $params  = array();
        $params['user_id'] = $user_id;
        
        /**
         * 查询 注册信息 
         */
        $info_data = $bcsRegister_model->getInfo($params);
        if(EC_OK != $info_data['code']){
            Log::error("getInfo failed . ");
            EC::fail($info_data['code']);
        }
        $info_data = $info_data['data'][0];
        if(empty($info_data)){
            Log::error("getInfo failed . obj is empty .");
            EC::fail($info_data['code']);
        }
        $sit_no = $info_data['SIT_NO']; // 席位号
        
        /**
         * 调用接口，查询 客户信息 
         */
        Log::notice('loadInfo-str ==== >>> getCustomerInfo sit_no=##' . $sit_no . '##');
        $bcs_data = $bcsBank_model->getCustomerInfo( $mch_no, $sit_no );
        Log::notice('loadInfo-end ==== >>> getCustomerInfo response=##' . json_encode($bcs_data) . '##');
        if(false == $bcs_data || !empty($bcs_data['code'])){
            Log::error("getCustomerInfo failed . ");
            EC::fail($bcs_data['code']);
        }
        $bcs_data = $bcs_data['data'];
        
        $params['ACCOUNT_NO'] = $bcs_data['ACCOUNT_NO']; // 客户虚拟账号
//         $params['SIT_NO'] = $bcs_data['SIT_NO']; // 客户席位号
        $params['MBR_STS'] = 2;//$bcs_data['MBR_STS']; // 客户状态 1-已注册；2-已签约；3-已注销
        $params['MBR_CERT_TYPE'] = $bcs_data['MBR_CERT_TYPE']; // 会员证件类型
        $params['MBR_CERT_NO'] = $bcs_data['MBR_CERT_NO']; // 会员证件号码
        $params['MBR_NAME'] = $bcs_data['MBR_NAME']; // 会员名称
        $params['MBR_SPE_ACCT_NO'] = $bcs_data['MBR_SPE_ACCT_NO']; // 会员指定账号（客户结算账号）
        $params['MBR_SPE_ACCT_NAME'] = $bcs_data['MBR_SPE_ACCT_NAME']; // 会员指定户名
        $params['MBR_BANK_NAME'] = $bcs_data['MBR_BANK_NAME']; // 行名
        $params['MBR_BANK_NO'] = $bcs_data['MBR_BANK_NO']; // 行号
        $params['MBR_ADDR'] = empty($bcs_data['MBR_ADDR'])?'':$bcs_data['MBR_ADDR']; // 会员联系地址
        $params['MBR_TELENO'] = empty($bcs_data['MBR_TELENO'])?'':$bcs_data['MBR_TELENO']; // 电话
        $params['MBR_PHONE'] = empty($bcs_data['MBR_PHONE'])?'':$bcs_data['MBR_PHONE']; // 手机号
        $params['ACCT_BAL'] = $bcs_data['ACCT_BAL']; // 余额
        $params['AVL_BAL'] = $bcs_data['AVL_BAL']; // 可用余额
        $params['SIGNED_DATE'] = strval($bcs_data['SIGNED_DATE']); // 开户日期
        $params['ACT_TIME'] = strval($bcs_data['ACT_TIME']); // 签约时间（时间格式：YYYY-MM-DD HH24:MI:SS）
        
        Log::error('----------------------------------------------------------------params==>>' . var_export($params, true));
        if(empty($params['ACCOUNT_NO'])) {
            Log::error("getCustomerInfo failed [ACCOUNT_NO] is empty . ");
            EC::fail($bcs_data['code']);
        }
        
        /**
         * 更新 客户信息
         */
        $upd_data = $bcsCustomer_model->update($params);
        if(EC_OK != $upd_data['code']){
            Log::error("update failed . ");
            EC::fail($upd_data['code']);
        }
        
        Log::notice('loadInfo ==== >>> upd_data=' . json_encode($upd_data) );
        EC::success(EC_OK);
    }
    
    // 更新浦发银行账户(余额)列表
    protected function spd_loadAccountList() {
        $virtualAcctNo = Request::post('virtualAcctNo');
        $user_id = Request::post('user_id');
        
        if( empty($virtualAcctNo) || empty($user_id) ) {
            $post_data = getPostStr();
            if(!empty($post_data)){
                $post_data = json_decode($post_data,true)['data'];
                $virtualAcctNo = $post_data['virtualAcctNo'];
                $user_id = $post_data['user_id'];
            }
        }
        
        $bcsCustomer_model = $this->model('bcsCustomer');
        $spdBank_model = $this->model('spdBank');
        $conf = $this->getConfig('conf');
        
        $params = array();
        $params['beginNumber'] = 1;
        $params['queryNumber'] = 20;
        $params['virtualAcctNo'] = '';
        
        if( !empty($virtualAcctNo) ) {
            $params['virtualAcctNo'] = $virtualAcctNo;
        } else if( !empty($user_id) ) {
            $data = $bcsCustomer_model->getInfo(array('user_id' => $user_id));
            if(EC_OK != $data['code']){
                Log::error("getInfo failed . ");
                EC::fail($data['code']);
            }
            $data_info = $data['data'][0];
            Log::notice("postRequest data ==================data_info=========>> data = ##" . json_encode($data_info) . "##" );
            $params['virtualAcctNo'] = $data_info['ACCOUNT_NO'];
        }
        Log::notice("postRequest data ==================params=========>> data = ##" . json_encode($params) . "##" );
//         exit;
        
        $totalNumber = 0 ;
        do {
            $data = $spdBank_model->queryChildAccount($params);
//             Log::notice('spd_loadAccountList ==== >>> data=##' . json_encode($data) . "##");
            
            $totalNumber = $data['body']['totalNumber'];
            $data_lists = $data['body']['lists']['list'];
            
            $this->addCustomerList($data_lists);
            $params['beginNumber'] = $params['beginNumber'] + $params['queryNumber'] ;
        } while ( $totalNumber >= $params['beginNumber']);
        
        EC::success(EC_OK);
    }
    
    public function addCustomerList($data_lists = array()){
        if(empty($data_lists)){
            Log::notice("addCustomerList data_lists is empty . ");
            return ;
        }
        
        if(!empty($data_lists['acctNo'])) {
            $data_lists_temp = array();
            $data_lists_temp[] = $data_lists;
            $data_lists = $data_lists_temp;
        }
        
        $bcsCustomer_model = $this->model('bcsCustomer');
        foreach($data_lists as $obj ){
            $customer = array();
            $customer['record_bank_type'] = 2; // 1-bcs长沙银行 2-psd浦发银行
            $customer['ACCOUNT_NO'] = $obj['virtualAcctNo']; // 虚账号
            
            $info_data = $bcsCustomer_model->getInfo($customer);
            if(EC_OK != $info_data['code']){
                Log::error("getInfo failed . virtualAcctNo-ACCOUNT_NO=" . $customer['ACCOUNT_NO'] . ',code='. $info_data['code'] . ',msg=' . $info_data['msg'] );
                continue;
            }
            
            $customer['ACCT_BAL'] = $obj['accountBalance']; // 帐户余额
            $customer['AVL_BAL'] = $obj['accountBalance'];
            $customer['SIT_NO'] = $obj['virtualAcctName']; // 虚账户名称
            $customer['MBR_STS'] = 2; // 客户状态 1-已注册；2-已签约；3-已注销
            
            $info_data = $info_data['data'][0];
            if( !empty($info_data) ){
                $upd_data = $bcsCustomer_model->update($customer);
                if(EC_OK != $upd_data['code']){
                    Log::error("getInfo failed . virtualAcctNo-ACCOUNT_NO=" . $customer['ACCOUNT_NO'] . ',code='. $upd_data['code'] . ',msg=' . $upd_data['msg'] );
                    continue;
                }
            } else {
                $data_rs = $bcsCustomer_model->create($customer);
                if($data_rs['code'] !== EC_OK){
                    Log::error('addCustomerList . create bcsCustomer error . code='. $data_rs['code'] . ',msg=' . $data_rs['msg'] );
                    continue;
                }
            }
            Log::notice('addCustomerList ==== >>> add-data=##' . $obj['virtualAcctName'] . "##");
        }
    }
    
    protected function transfer() {
        $amount = Request::post('amount');
        $pwd = Request::post('pwd');
        $inOut = Request::post('inOut');
        
        if(!$amount || !$pwd || !$inOut){
            Log::error('transfer params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        if(BcsTransferModel::$_transfer_type_out != $inOut && BcsTransferModel::$_transfer_type_in != $inOut) {
            Log::error('transfer params error! inOut=' . $inOut);
            EC::fail(EC_PAR_ERR);
        }
        
        if( !is_numeric($amount) ){
            Log::error('transfer params error! amount=' . $amount);
            EC::fail(EC_PAR_ERR);
        }
        
        if( 5 < settype($amount,"double") ){
            Log::error('transfer params error! [for test] amount=' . $amount);
            EC::fail(EC_PAR_ERR);
        }
        
        
        $bcsBank_model = $this->model('bank');
        $bcsCustomer_model = $this->model('bcsCustomer');
        $bcsRegister_model = $this->model('bcsRegister');
        $bcsTransfer_model = $this->model('bcsTransfer');
        $conf = $this->getConfig('conf');
    
        
        /**
         * 验证 密码 
         */
        // TODO 
        
        $user_id = self::getCurrentUserId();
        $mch_no = $conf['MCH_NO']; // 商户编号
    
        $params  = array();
        $params['user_id'] = $user_id;
    
        Log::error('----------------------------------------------------------------params==>>' . var_export($params, true));
        /**
         * 查询 注册信息
         */
        $info_data = $bcsRegister_model->getInfo($params);
        if(EC_OK != $info_data['code']){
            Log::error("getInfo failed . ");
            EC::fail($info_data['code']);
        }
        $info_data = $info_data['data'][0];
        if(empty($info_data)){
            Log::error("getInfo failed . obj is empty .");
            EC::fail($info_data['code']);
        }
        $sit_no = $info_data['SIT_NO']; // 席位号
        
        Log::error('------------------------------------info_data----------------------------params==>>' . var_export($info_data, true));
        /**
         * 增加 客户出入金 记录
         */
        $mch_trans_no = 'D' . date('Ymd',time()) . 'T' . date('His',time()) . 'R' . rand(100,999) . 'U' . $user_id;; // 交易流水，需保证唯一性
        $curr_cod = BcsTransferModel::$_CURR_COD_RMB; // 币别 目前只支持：01-人民币
        $trans_amt = floatval($amount);
        
        $params['transfer_type'] = $inOut; // 客户出入金
        $params['MCH_NO'] = $mch_no; // 商户编号
        $params['SIT_NO'] = $sit_no; // 席位号
        $params['MCH_TRANS_NO'] = $mch_trans_no; // 商户交易流水号
        $params['CURR_COD'] = $curr_cod; // 币别 目前只支持：01-人民币
        $params['TRANS_AMT'] = $trans_amt; // 交易金额 单位:元
        
        $params['comment'] = BcsTransferModel::$_comment_build;
        $params['status'] = BcsTransferModel::$_status_unknown;
        
        $data = $bcsTransfer_model->create($params);
        if(EC_OK != $data['code']){
            Log::error('create-transfer Fail!');
            EC::fail($data['code']);
        }
        $bcs_transfer_id = $data['data'];
        $params['id'] = $bcs_transfer_id;
        Log::notice('create-transfer success . id=' . $bcs_transfer_id );
        
        /**
         * 调用接口，查询 客户信息
         */
        // 客户入金
        if(BcsTransferModel::$_transfer_type_in == $inOut) {
            Log::notice('loadInfo-str ==== >>> customerInflow sit_no=##' . $sit_no . '##');
            $bcs_data = $bcsBank_model->customerInflow( $mch_no, $sit_no, $mch_trans_no, $curr_cod, $trans_amt );
            Log::notice('loadInfo-end ==== >>> customerInflow response=##' . json_encode($bcs_data) . '##');
            if(false == $bcs_data || !empty($bcs_data['code'])){
                Log::error("customerInflow failed . ");
                EC::fail($bcs_data['code']);
            }
            
            $bcs_data = $bcs_data['data'];
            
            $params['MCH_TRANS_NO'] = $bcs_data['MCH_TRANS_NO']; // 商户交易流水号
            $params['FMS_TRANS_NO'] = $bcs_data['FMS_TRANS_NO']; // 资金监管系统交易流水号
            $params['TRANS_STS'] = $bcs_data['TRANS_STS']; // 交易状态 1:交易成功；2：交易失败；3：处理中
            $params['TRANS_AMT'] = $bcs_data['TRANS_AMT']; // 交易金额 单位:元
            $params['TRANS_TIME'] = $bcs_data['TRANS_TIME']; // 交易完成时间 时间格式：YYYY-MM-DD HH24:MI:SS
            
            $params['comment'] = BcsTransferModel::$_comment_success;
            $params['status'] = BcsTransferModel::$_status_success;
            $params['transfer_type'] = BcsTransferModel::$_transfer_type_in; // 入金
        } 
        // 客户出金
        else if(BcsTransferModel::$_transfer_type_out == $inOut) {
            $trans_fee = 0; // 手续费
            
            Log::notice('loadInfo-str ==== >>> customerInflow sit_no=##' . $sit_no . '##');
            $bcs_data = $bcsBank_model->customerOutflow( $mch_no, $sit_no, $mch_trans_no, $curr_cod, $trans_amt, $trans_fee );
            Log::notice('loadInfo-end ==== >>> customerOutflow response=##' . json_encode($bcs_data) . '##');
            if(false == $bcs_data || !empty($bcs_data['code'])){
                Log::error("customerOutflow failed . ");
                EC::fail($bcs_data['code']);
            }
            
            $bcs_data = $bcs_data['data'];
            
            $params['MCH_TRANS_NO'] = $bcs_data['MCH_TRANS_NO']; // 商户交易流水号
            $params['FMS_TRANS_NO'] = $bcs_data['FMS_TRANS_NO']; // 资金监管系统交易流水号
            $params['TRANS_STS'] = $bcs_data['TRANS_STS']; // 交易状态 1:交易成功；2：交易失败；3：处理中
            $params['TRANS_AMT'] = $bcs_data['TRANS_AMT']; // 交易金额 单位:元
            $params['TOTALAMT'] = $bcs_data['TOTALAMT']; // 手续费金额 单位：元（保留两位小数）
            $params['TRANS_TIME'] = $bcs_data['TRANS_TIME']; // 交易完成时间 时间格式：YYYY-MM-DD HH24:MI:SS
            
            $params['comment'] = BcsTransferModel::$_comment_success;
            $params['status'] = BcsTransferModel::$_status_success;
            $params['transfer_type'] = BcsTransferModel::$_transfer_type_out; // 出金
        } 
    
        if(empty($params['FMS_TRANS_NO'])) {
            Log::error("getCustomerInfo failed [FMS_TRANS_NO] is empty . ");
            EC::fail($bcs_data['code']);
        }
    
        /**
         * 更新 客户出入金 记录
         */
        $upd_data = $bcsTransfer_model->update($params);
        if(EC_OK != $upd_data['code']){
            Log::error("update failed . ");
            EC::fail($upd_data['code']);
        }
        Log::notice('update-transfer success . id=' . $params['id'] );
        
        Log::notice('loadInfo ==== >>> upd_data=' . json_encode($upd_data) );
        EC::success(EC_OK);
    }

    private function getInflow(){
        $loginUser = UserController::getLoginUser();
        if(!$SIT_NO = $loginUser['SIT_NO']){ EC::fail(EC_SIT_NO_NON);}
        $params = [
            'MCH_NO'      => $this->getConfig('conf')['MCH_NO'],
            'SIT_NO'      => $SIT_NO,
            'PAGE_SIZE'   => 10,
            'PAGE_NUMBER' => $this->post('page',1)
        ];

        $bcs_data = $this->model('bank')->customerInflowQuery($params);
        Log::error('getInflow<<<<'.var_export($bcs_data['data'],true));
        $data = $bcs_data['code'] === 0 ? $bcs_data['data'] : ['List' => []];
        if($bcs_data['code'] !== 0){
            Log::bcsError('getInflow error code('.$bcs_data['code'].')'.' msg：'.$bcs_data['msg']);
            IS_POST && EC::fail(EC_OTH);
        }

        IS_POST && EC::success(EC_OK,$data);
        $inflow_html = $this->render('bcsCustomerInflow',['data' => $data],true);
        $this->render('index',['page_type' => 'bcsCustomerInflow' ,'bcsCustomerInflow_html' => $inflow_html]);
    }

    private function getIncomePay(){
        $loginUser = UserController::getLoginUser();
        if(!$SIT_NO = $loginUser['SIT_NO']){ EC::fail(EC_SIT_NO_NON);}
        $params   = [
            'MCH_NO' => $this->getConfig('conf')['MCH_NO'],
            'SIT_NO' => $SIT_NO,
            'PAGE_NUMBER' => $this->post('page',1),
            'PAGE_SIZE' => 10
        ];
        $bcs_data = $this->model('bank')->customerIncomePayQuery('1',$params);
        $data = $bcs_data['code'] === 0 ? $bcs_data['data'] : ['List' => []];
        if($bcs_data['code'] !== 0){
            Log::bcsError('getIncomePay error code('.$bcs_data['code'].')'.' msg：'.$bcs_data['msg']);
            IS_POST && EC::fail(EC_OTH);
        }

        IS_POST && EC::success(EC_OK,$data);
        $incomePay_html = $this->render('bcsCustomerIncomPay',['data' => $data],true);
        $this->render('index',['page_type' => 'bcsCustomerIncomePay' ,'bcsCustomerIncomePay_html' => $incomePay_html]);
    }

    private function getAllList(){
        $record_bank_type = Request::post('record_bank_type');
        
        $bcsCustomer_model = $this->model('bcsCustomer');
        
        $params = array();
        $params['record_bank_type'] = $record_bank_type;
        $params['user_id'] = -1;
        
        $data = $bcsCustomer_model->searchList($params, null, null);
        if(EC_OK != $data['code']){
            EC::fail($data['code']);
        }
        EC::success(EC_OK,$data['data']);
    }
    
    private function updateBind(){
        $account = Request::post('account');
        $ACCOUNT_NO = Request::post('ACCOUNT_NO');
    
        $bcsCustomer_model = $this->model('bcsCustomer');
    
        $params = array();
        $params['user_id'] = $account;
        $params['ACCOUNT_NO'] = $ACCOUNT_NO;
        Log::notice("updateBind data ===============================>> params = ##" . json_encode($params) . "##" );
        
        $data = $bcsCustomer_model->updateBild($params);
        if(EC_OK != $data['code']){
            EC::fail($data['code']);
        }
        EC::success(EC_OK,$data['data']);
    }
    
}