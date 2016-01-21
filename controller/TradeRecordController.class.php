<?php
/**
 * 交易记录
 * @author zhangkui
 *
 */
class TradeRecordController extends BaseController {

    public function handle($params = array()) {
        Log::notice('TradeRecordController  ==== >>> params=' . json_encode($params));
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
                case 'delete':
                    $this->delete();
                    break;
                case 'create':  // 不需要 登录
                    $this->create();
                    break;
                case 'pay':
                    $this->pay();
                    break;
                case 'exportData':
                    $this->exportData();
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
    
        $tradeRecord_model = $this->model('tradeRecord');
        $user_id = self::getCurrentUserId();
    
        if($isIndex && !$order_status) {
            $order_status = TradeRecordModel::$_status_waiting;
        }
        
        $params  = array();
        foreach ([ 'order_no', 'user_id', 'code', 'time1', 'time2', 'type', 'order_status',
                    'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        $data_cnt = $tradeRecord_model->searchCnt($params);
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
        $data = $tradeRecord_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_list = $data['data'];
        $entity_list_html = $this->render('tradeRecord_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            
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
            
            $view_html = $this->render('tradeRecord', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'tradeRecord', 'tradeRecord_html' => $view_html, 'bcsCustomerInfo' => $data_info) );
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
        
        $tradeRecord_model = $this->model('tradeRecord');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'order_no', 'user_id', 'code', 'time1', 'time2', 'type', 'order_status',
            'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        if(TradeRecordModel::$_export_type_page == $export_type) {
            $data_cnt = $tradeRecord_model->searchCnt($params);
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
        $data = $tradeRecord_model->searchList($params);
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
    
        $tradeRecord_model = $this->model('tradeRecord');
        $user_id = self::getCurrentUserId();
    
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
    
        if(empty($params)){
            Log::error('update params is empty!');
            EC::fail(EC_PAR_BAD);
        }
    
        $data_old = $tradeRecord_model->getInfo($params);
        if(EC_OK != $data_old['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_old['code']);
        }
        $data_obj = $data_old['data'][0];
        if(empty($data_obj)) {
            Log::error('getInfo empty !');
            EC::fail(EC_RED_EMP);
        }
        if( TradeRecordModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
        if( TradeRecordModel::$_status_waiting != $data_obj['order_status'] ) {
            Log::error('record status is exception . status=' . $data_obj['order_status']);
            EC::fail(EC_RED_EXP);
        }
    
        $params['order_status'] = TradeRecordModel::$_status_refuse;
        $params['disenabled_timestamp'] = date('Y-m-d H:i:s',time());
    
        Log::notice('changeStatus ==== >>> params=' . json_encode($params) );
        $data = $tradeRecord_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }
    
    private function delete(){
        $id = Request::post('id');
    
        if(!$id){
            Log::error('delete params error!');
            EC::fail(EC_PAR_ERR);
        }
    
        $tradeRecord_model = $this->model('tradeRecord');
        $user_id = self::getCurrentUserId();
    
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
    
        if(empty($params)){
            Log::error('delete params is empty!');
            EC::fail(EC_PAR_BAD);
        }
    
        $data_old = $tradeRecord_model->getInfo($params);
        if(EC_OK != $data_old['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_old['code']);
        }
        $data_obj = $data_old['data'];
        if(empty($data_obj)) {
            Log::error('getInfo empty !');
            EC::fail(EC_RED_EMP);
        }
        if( TradeRecordModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
    
        $params['is_delete'] = TradeRecordModel::$_is_delete_true;
    
        Log::notice('changeStatus-delete ==== >>> params=' . json_encode($params) );
        $data = $tradeRecord_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }    
    
    protected function getInfo() {
        $id = Request::post('id');
    
        $tradeRecord_model = $this->model('tradeRecord');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'user_id', 'id' ] as $val){
            if($$val) $params[$val] = $$val;
        }
        
        $data = $tradeRecord_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
    
        $data_info = $data['data'][0];
        $entity_list_html = $this->render('tradePay', array('item' => $data_info), true);
        EC::success(EC_OK, array('tradeRecord_pay' => $entity_list_html));
    }
    
    private function create(){
        
        EC::success(EC_OK);
        
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
        
        /**
         * 验证 授权码 
         */
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
    
        /**
         * 验证 重复记录
         */
        $tradeRecord_model = $this->model('tradeRecord');
        $data = $tradeRecord_model->searchList(array('order_no' => $order_no));
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
        $data_list = $data['data'];
        if(!empty($data_list)) {
            foreach ($data_list as $item){
                Log::error("repetition trade record . order_no=" . $item['order_no'] . ",status=" . $item['status'] . ",id=" . $item['id'] );
            }
            EC::fail(EC_REC_EST);
        }
        
        /**
         *  增加 交易订单记录 
         */
        $params = array();
        $params['user_id'] = $code_obj['user_id']; // 授权码 所属用户ID
        $params['code_id'] = $code_obj['id']; // 授权码 ID
        $params['code_used_count'] = $code_obj['used_count']; // 授权码 已经使用次数
        $params['order_status'] = TradeRecordModel::$_status_waiting; // 订单交易状态 1-待付款
        $params['pay_timestamp'] = TradeRecordModel::$_empyt_time; // 操作（付款/拒付）时间
        foreach ([ 'code', 'seller_id', 'seller_name', 'seller_conn_name', 'seller_tel', 'seller_comp_phone',
                    'order_no', 'order_timestamp', 'order_goods_name', 'order_goods_size', 'order_goods_type', 'order_goods_price', 'order_goods_count',
                    'order_delivery_addr', 'order_sum_amount' ] as $val ){
            if($$val) $params[$val] = $$val;
        }
    
        if(empty($params)){
            Log::error('create params is empty!');
            EC::fail(EC_PAR_BAD);
        }
        
        $data = $tradeRecord_model->create($params);
        if(EC_OK != $data['code']){
            Log::error('create Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK,$data['data']);
    }
    
    private function pay(){
        $id = Request::post('id');
        $pwd = Request::post('pwd');
        
        if( !$id || !$pwd ){
            Log::error('checkCode params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        /**
         * 验证密码
         */
        
        $user_model = $this->model('user');
        $user_id = self::getCurrentUserId();
        
        $curr_user_data = $user_model->getInfo(array('id' => $user_id));
        if(EC_OK != $curr_user_data['code']){
            Log::error("getUserInfo failed . ");
            EC::fail($curr_user_data['code']);
        }
        $curr_user_info = $curr_user_data['data'][0];
        if(empty($curr_user_info)){
            Log::error("getUserInfo empty . user_id=" . $user_id);
            EC::fail(EC_USR_NON);
        }
        
        $decrypted_pwd = '';
		$privateKey  = openssl_pkey_get_private(self::getConfig('conf')['private_key']);
		$payPassword = base64_decode($pwd);
		openssl_private_decrypt($payPassword, $decrypted_pwd, $privateKey);
        if(!$decrypted_pwd){
            Log::error('setPayPassword password is empty');
            EC::fail(EC_PWD_EMP);
        }
        if(!password_verify($decrypted_pwd,$curr_user_info['pay_password']) ){
            Log::error('PayPassword is wrong .');
            EC::fail(EC_PWD_WRN);
        }
        
        /**
         * 验证 订单 状态
         */
        
        $tradeRecord_model = $this->model('tradeRecord');
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
        
        if(empty($params)){
            Log::error('update params is empty!');
            EC::fail(EC_PAR_BAD);
        }
        
        $data_old = $tradeRecord_model->getInfo($params);
        if(EC_OK != $data_old['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_old['code']);
        }
        $data_obj = $data_old['data'][0];
        if(empty($data_obj)) {
            Log::error('getInfo empty !');
            EC::fail(EC_RED_EMP);
        }
        if( TradeRecordModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
        if( TradeRecordModel::$_status_waiting != $data_obj['order_status'] ) {
            Log::error('record status is exception . status=' . $data_obj['order_status']);
            EC::fail(EC_RED_EXP);
        }
        
        /**
         * 检查 付款记录
         */
        $bcsTrade_model = $this->model('bcsTrade');
        $params_trade_select = array();
        $params_trade_select['order_id'] = $data_obj['order_no'] . '-' . $id;
        $params_trade_select['status'] = BcsTradeModel::$_status_unknown . BcsTradeModel::$_status_success ;
        $data = $bcsTrade_model->searchList($params_trade_select);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
        $data_list = $data['data'];
        if(!empty($data_list)) {
            foreach ($data_list as $item){
                Log::error("repetition trade . order_id=" . $item['order_id'] . ",status=" . $item['status'] . ",id=" . $item['id'] . ',MCH_TRANS_NO=' . $item['MCH_TRANS_NO'] );
            }
            EC::fail(EC_BCS_TRADE_REPE);
        }

        /**
         * 组装交易信息
         */
        
        $params['order_status'] = TradeRecordModel::$_status_paid;
        $params['disenabled_timestamp'] = date('Y-m-d H:i:s',time());
        
        $conf = $this->getConfig('conf');
        // 商户编号
        $mch_no = $conf['MCH_NO'];
        
        $bcsRegister_model = $this->model('bcsRegister');
        // 付款方用户ID
        $b_user_id = $user_id; // 当前用户
        
        $data = $bcsRegister_model->getInfo(array('user_id' => $b_user_id));
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
        $b_data_info = $data['data'][0];
        // 付款方席位号
        $buyer_sit_no = $b_data_info['SIT_NO'];
        //检查买方帐号以及余额
        $buyer_bank_info = $this->model('bank')->getCustomerInfo($mch_no,$buyer_sit_no);
        if($buyer_bank_info['code']!==0){
            Log::error('get buyer bank info error code:'.$buyer_bank_info['code']);
            EC::fail(EC_OTH);
        }

        if($buyer_bank_info['data']['MBR_STS'] == '2'){//此处应该是1
            Log::error('buyer bank not sign status:'.$buyer_bank_info['data']['MBR_STS']);
            EC::fail(EC_NOT_SIGN);
        }else if($buyer_bank_info['data']['MBR_STS'] == '3'){
            Log::error('buyer bank already cancel status:'.$buyer_bank_info['data']['MBR_STS']);
            EC::fail(EC_ARY_CANCEL);
        }else if($buyer_bank_info['data']['ACCT_BAL'] < $data_obj['order_sum_amount']){
            Log::error('buyer bank balance less');
            EC::fail(EC_BLE_LESS);
        }

        // 收款方用户ID
        $params  = array();
        $params['account'] = $data_obj['seller_tel'];
        $data = $user_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getUserInfo failed . ");
            EC::fail($data['code']);
        }
        $data_info = $data['data'][0];
        if(empty($data_info)){
            Log::error("getUserInfo empty . account=" . $params['account']);
            EC::fail(EC_USR_NON);
        }
        
        $s_user_id = $data_info['id']; // TODO 按照  电话 对应到 收款用户
        $data = $bcsRegister_model->getInfo(array('user_id' => $s_user_id));
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
        $s_data_info = $data['data'][0];
        // 收款方席位号
        $seller_sit_no = $s_data_info['SIT_NO'];
        //检查收款方帐号
        $seller_bank_info = $this->model('bank')->getCustomerInfo($mch_no,$seller_sit_no);
        if($seller_bank_info['code']!==0){
            Log::error('get seller bank info error code:'.$seller_bank_info['code']);
            EC::fail(EC_OTH);
        }

        if($seller_bank_info['data']['MBR_STS'] == '2'){ //此处应该是1
            Log::error('seller bank not sign status:'.$seller_bank_info['data']['MBR_STS']);
            EC::fail(EC_NOT_SIGN);
        }else if($seller_bank_info['data']['MBR_STS'] == '3'){
            Log::error('seller bank already cancel status:'.$seller_bank_info['data']['MBR_STS']);
            EC::fail(EC_ARY_CANCEL);
        }

        // 付款订单号
        $order_no = $data_obj['order_no'];
        // 付款订单总金额
        $order_sum_amount = $data_obj['order_sum_amount'];
        
        // 订单编号
        $ctrt_no = 'D' . date('Ymd',time()) . 'T' . date('His',time()) . 'N' . $order_no;
        // 商户交易流水号
        $mch_trans_no = 'D' . date('Ymd',time()) . 'T' . date('His',time()) . 'R' . rand(100,999) . 'U' . $user_id;
        
        $params_trade = array();
        $params_trade['MCH_NO'] = $mch_no; // 商户编号
        $params_trade['CTRT_NO'] = $ctrt_no; // 订单编号
        $params_trade['BUYER_SIT_NO'] = $buyer_sit_no; // 付款方席位号
        $params_trade['SELLER_SIT_NO'] = $seller_sit_no; // 收款方席位号
        $params_trade['FUNC_CODE'] = BcsTradeModel::$_FUNC_CODE_FINISH; // 功能号
        $params_trade['TX_AMT'] = $order_sum_amount; // 交易金额
       // $params_trade['TX_AMT'] = 1; // TODO for test .
        $params_trade['SVC_AMT'] = BcsTradeModel::$_SVC_AMT_0; // 买方佣金金额
        $params_trade['BVC_AMT'] = BcsTradeModel::$_BVC_AMT_0; // 卖方佣金金额
        $params_trade['CURR_COD'] = BcsTradeModel::$_CURR_COD_RMB; // 币别
        $params_trade['MCH_TRANS_NO'] = $mch_trans_no; // 商户交易流水号
        $params_trade['ORGNO'] = '0'; // 银票机构编号
        $params_trade['TICKET_NUM'] = BcsTradeModel::$_TICKET_NUM_0; // 使用票据数
        
        $params['bcs_trade'] = $params_trade;
        $params['order_id'] = $order_no . '-' . $id;
        $params['order_no'] = $order_no;
        $params['b_user_id'] = $b_user_id;
        $params['s_user_id'] = $s_user_id;
        $params['seller_name'] = $data_obj['seller_name']; // 卖家(公司)名称
        $params['comment'] = BcsTradeModel::$_comment_build;
        $params['status'] = BcsTradeModel::$_status_unknown;
        
        /**
         * 增加 交易付款
         */
        $data = $bcsTrade_model->create($params);
        if(EC_OK != $data['code']){
            Log::error('create-pay Fail!');
            EC::fail($data['code']);
        }
        $bcs_trade_id = $data['data'];
        $params['bcs_trade_id'] = $bcs_trade_id;
        
        /**
         * 支付（转账）
         */
        $bcsBank_model = $this->model('bank');
        Log::notice('loadInfo-str ==== >>> notFrozenSpotsTradePay params_trade=##' . json_encode($params_trade) . '##');
        $bcs_data = $bcsBank_model->notFrozenSpotsTradePay($params_trade);
        Log::notice('loadInfo-end ==== >>> notFrozenSpotsTradePay response=##' . json_encode($bcs_data) . '##');
        if(false == $bcs_data || !empty($bcs_data['code'])){
            Log::error("notFrozenSpotsTradePay failed . ");
            EC::fail($bcs_data['code']);
        }
        $bcs_data = $bcs_data['data'];
//         Log::notice('loadInfo ==== >>> notFrozenSpotsTradePay params_trade=##' . json_encode($params_trade) . '##');
//         $bcs_data['FMS_TRANS_NO'] = '201511136070100049291';
//         $bcs_data['TRANS_TIME'] = '2015-11-13 16:34:58';
        
        if(empty($bcs_data['FMS_TRANS_NO'])){
            Log::error("notFrozenSpotsTradePay failed [FMS_TRANS_NO] is empty . ");
            EC::fail($bcs_data['code']);
        }
        $params['FMS_TRANS_NO'] = $bcs_data['FMS_TRANS_NO']; // 资金监管系统交易流水号
        $params['TRANS_TIME'] = $bcs_data['TRANS_TIME']; // 交易完成时间 时间格式：YYYY-MM-DD HH24:MI:SS
        $params['comment'] = BcsTradeModel::$_comment_success;
        $params['status'] = BcsTradeModel::$_status_success;
        unset($params['bcs_trade']);
        
        /**
         * 更新 交易付款
         */
        Log::notice('bcsTrade-update . params==>>' . var_export($params, true));
        $data = $bcsTrade_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update-bcsTrade Fail! code=' . $data['code'] . ',msg=' . $data['msg'] ); // 仅仅记录日志，因为 实际交易已经成功。
        }
        
        /**
         * 修改 支付订单信息 
         */
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
        $params['order_status'] = TradeRecordModel::$_status_paid;
        Log::notice('tradeRecord-update . params==>>' . var_export($params, true));
        $data = $tradeRecord_model->pay($params);
        if(EC_OK != $data['code']){
            Log::error('update-TradeRecord Fail! code=' . $data['code'] . ',msg=' . $data['msg'] ); // 仅仅记录日志，因为 实际交易已经成功。
        }
        
        EC::success(EC_OK);
    }
    
}