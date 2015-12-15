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
                case 'changeStatus':
                    $this->changeStatus();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'create':
                    $this->create();
                    break;
                case 'loadInfo':
                    $this->loadInfo();
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
    
        $bcsCustomer_model = $this->model('bcsCustomer');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'order_no', 'user_id', 'code', 'time1', 'time2', 'type', 'order_status',
                    'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2' ] as $val){
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
    
        $current_page = 1;
        $page_cnt = 1;
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
        $id = Request::post('id');
    
        $bcsCustomer_model = $this->model('bcsCustomer');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'user_id', 'id' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        $data = $bcsCustomer_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_info = $data['data'][0];
        $entity_list_html = $this->render('tradePay', array('item' => $data_info), true);
        EC::success(EC_OK, array('bcsCustomer_pay' => $entity_list_html));
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
        $user_id = self::getCurrentUserId();
        
        $params  = array();
        $params['user_id'] = $user_id;
        
        $info_data = $bcsCustomer_model->getInfo($params);
        if(EC_OK != $info_data['code']){
            Log::error("getInfo failed . ");
            EC::fail($info_data['code']);
        }
        Log::notice('loadInfo ==== >>> info_data=' . json_encode($info_data) );
        $info_data = $info_data['data'][0];
        
        $mch_no = '198209';
        $sit_no = $info_data['SIT_NO'];
        
        $bcs_data = $bcsBank_model->getCustomerInfo( $mch_no, $sit_no );
        Log::notice('loadInfo ==== >>> bcs_data=' . json_encode($bcs_data) );
        if(EC_OK != $bcs_data['code']){
            Log::error("getCustomerInfo failed . ");
            EC::fail($bcs_data['code']);
        }
        $bcs_data = $bcs_data['data'];
        
        $params['ACCOUNT_NO'] = $bcs_data['ACCOUNT_NO']; // 客户虚拟账号
        $params['SIT_NO'] = $bcs_data['SIT_NO']; // 客户席位号
        $params['MBR_STS'] = $bcs_data['MBR_STS']; // 客户状态 1-已注册；2-已签约；3-已注销
        $params['MBR_CERT_TYPE'] = $bcs_data['MBR_CERT_TYPE']; // 会员证件类型
        $params['MBR_CERT_NO'] = $bcs_data['MBR_CERT_NO']; // 会员证件号码
        $params['MBR_NAME'] = $bcs_data['MBR_NAME']; // 会员名称
        $params['MBR_SPE_ACCT_NO'] = $bcs_data['MBR_SPE_ACCT_NO']; // 会员指定账号（客户结算账号）
        $params['MBR_SPE_ACCT_NAME'] = $bcs_data['MBR_SPE_ACCT_NAME']; // 会员指定户名
        $params['MBR_BANK_NAME'] = $bcs_data['MBR_BANK_NAME']; // 行名
        $params['MBR_BANK_NO'] = $bcs_data['MBR_BANK_NO']; // 行号
        $params['MBR_ADDR'] = $bcs_data['MBR_ADDR']; // 会员联系地址
        $params['MBR_TELENO'] = $bcs_data['MBR_TELENO']; // 电话
        $params['MBR_PHONE'] = $bcs_data['MBR_PHONE']; // 手机号
        $params['ACCT_BAL'] = $bcs_data['ACCT_BAL']; // 余额
        $params['AVL_BAL'] = $bcs_data['ACCOUNT_NO']; // 可用余额
        $params['SIGNED_DATE'] = $bcs_data['SIGNED_DATE']; // 开户日期
        $params['ACT_TIME'] = $bcs_data['ACT_TIME']; // 签约时间（时间格式：YYYY-MM-DD HH24:MI:SS）
        
        $upd_data = $bcsCustomer_model->update($params);
        if(EC_OK != $upd_data['code']){
            Log::error("update failed . ");
            EC::fail($upd_data['code']);
        }
        
        Log::notice('loadInfo ==== >>> upd_data=' . json_encode($upd_data) );
        EC::success(EC_OK);
    }
    
}