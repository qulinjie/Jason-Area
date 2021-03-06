<?php
/**
 * @author zhangkui
 *
 */
class BcsTradeController extends BaseController {

    public function handle($params = array()) {
        Log::notice('BcsTradeController  ==== >>> params=' . json_encode($params));
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
                case 'getIndex_in':
                    $this->searchList(true,'1');
                    break;
                case 'getIndex_out':
                    $this->searchList(true,'0');
                    break;
//                 case 'getInfo':
//                     $this->getInfo();
//                     break;
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
//                 case 'exportData':
//                     $this->exportData();
//                     break;
                case 'tradeStatusQueryIndex':
                    $this->tradeStatusQuery(true);
                    break;
                case 'tradeStatusQuery':
                    $this->tradeStatusQuery();
                    break;
                    
                case 'spd_loadAccountTradeList':
                    $this->spd_loadAccountTradeList();
                    break;                    
                case 'erp_syncBillsOfCollection':
                	$this->erp_syncBillsOfCollection();
                	break;
                	    
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }
    
    protected function searchList_old($isIndex = false) {
        $current_page = Request::post('page');
        $seller_name = Request::post('seller_name'); // 收款方
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $order_no = Request::post('order_no');
        $status = Request::post('status');
        $amount1 = Request::post('amount1');
        $amount2 = Request::post('amount2');
        $FMS_TRANS_NO = Request::post('FMS_TRANS_NO');
        $b_account = Request::post('b_account');
        $s_account = Request::post('s_account');
        
        $bcsTrade_model = $this->model('bcsTrade');
        $user_model = $this->model('user');
        $b_user_id = null;
        $b_user_id_list = array(); // 付款方
        $s_user_id_list = array(); // 收款方
        if(AdminController::isAdmin()){
            // 付款方
            if($b_account && !empty($b_account)){
                $user_data = $user_model->searchList(array('account' => $b_account));
                if(EC_OK != $user_data['code']){
                    Log::error("searchList failed . ");
                } else {
                    $user_data = $user_data['data'];
                    if(empty($user_data)){
                        Log::notice("searchList is empty . ");
                        EC::success(EC_OK, array('entity_list_html' => $this->render('bcsTrade_list', array(), true) ));
                    } else {
                        foreach ($user_data as $key => $val){
                            $b_user_id_list[] = $user_data[$key]['id'];
                        }
                    }
                }
            }
            // 收款方
            if($s_account && !empty($s_account)){
                $user_data = $user_model->searchList(array('account' => $s_account));
                if(EC_OK != $user_data['code']){
                    Log::error("searchList failed . ");
                } else {
                    $user_data = $user_data['data'];
                    if(empty($user_data)){
                        Log::notice("searchList is empty . ");
                        EC::success(EC_OK, array('entity_list_html' => $this->render('bcsTrade_list', array(), true) ));
                    } else {
                        foreach ($user_data as $key => $val){
                            $s_user_id_list[] = $user_data[$key]['id'];
                        }
                    }
                }
            }
        } else {
            $b_user_id = self::getCurrentUserId();
        }
        
        $params  = array();
        foreach ([ 'b_user_id', 'seller_name', 'time1', 'time2', 'order_no', 'status',
                    'FMS_TRANS_NO', 'seller_name', 'amount1', 'amount2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
        
        if( $s_user_id_list && !empty($s_user_id_list) ){
            $params['s_user_id_list'] = $s_user_id_list;
        }
        if( $b_user_id_list && !empty($b_user_id_list) ){
            $params['b_user_id_list'] = $b_user_id_list;
        }
    
        $data_cnt = $bcsTrade_model->searchCnt($params);
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
        $data = $bcsTrade_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_list = $data['data'];
        
        /* // 用户账号 	用户名称 	用户公司名称
        if(!empty($data_list)){
            $s_user_id_list = array(); // 收款方
            $b_user_id_list = array(); // 付款方
            foreach($data_list as $key => $val){
                if( !in_array($data_list[$key]['s_user_id'], $s_user_id_list, true)){
                    $s_user_id_list[] = $data_list[$key]['s_user_id'];
                }
                if( !in_array($data_list[$key]['b_user_id'], $b_user_id_list, true)){
                    $b_user_id_list[] = $data_list[$key]['b_user_id'];
                }
            }
            // 收款方
            $user_data = $user_model->searchList(array('user_id_list' => $s_user_id_list));
            if(EC_OK != $user_data['code']){
                Log::error("searchList failed . ");
            } else {
                $user_data = $user_data['data'];
                foreach($data_list as $key1 => $val1){
                    foreach($user_data as $key2 => $val2){
                        if( $data_list[$key1]['s_user_id'] == $user_data[$key2]['id'] ){
                            $data_list[$key1]['s_account'] = $user_data[$key2]['account'];
                            $data_list[$key1]['s_nicename'] = $user_data[$key2]['nicename'];
                            $data_list[$key1]['s_company_name'] = $user_data[$key2]['company_name'];
                            break ;
                        }
                    }
                }
            }
            // 付款方
            $user_data = $user_model->searchList(array('user_id_list' => $b_user_id_list));
            if(EC_OK != $user_data['code']){
                Log::error("searchList failed . ");
            } else {
                $user_data = $user_data['data'];
                foreach($data_list as $key1 => $val1){
                    foreach($user_data as $key2 => $val2){
                        if( $data_list[$key1]['b_user_id'] == $user_data[$key2]['id'] ){
                            $data_list[$key1]['b_account'] = $user_data[$key2]['account'];
                            $data_list[$key1]['b_nicename'] = $user_data[$key2]['nicename'];
                            $data_list[$key1]['b_company_name'] = $user_data[$key2]['company_name'];
                            break ;
                        }
                    }
                }
            }
            
        } */
        
        $entity_list_html = $this->render('bcsTrade_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('bcsTrade', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'bcsTrade', 'bcsTrade_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
    protected function searchList($isIndex = false,$inout = '') {
        $current_page = Request::post('page');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $oppositeAcctName = Request::post('oppositeAcctName');
        $MCH_TRANS_NO = Request::post('MCH_TRANS_NO');
        //新增加金额、对方银行名等
        $txamt1 = Request::post('txamt1');
        $txamt2 = Request::post('txamt2');
        $paybankname = Request::post('paybankname');
        $erpfgsdm    = Request::post('erpfgsdm');
        $bcsTrade_model = $this->model('bcsTrade');
        $user_model = $this->model('user');
        $bcsCustomer_model = $this->model('bcsCustomer');
        
        $user_id = self::getCurrentUserId();
        $ACCOUNT_NO = '';
        if(AdminController::isAdmin()){
            
        } else {
            $customer = array();
            $customer['user_id'] = $user_id;
            $info_data = $bcsCustomer_model->getInfo($customer);
            if(EC_OK != $info_data['code']){
                Log::error("getInfo failed . virtualAcctNo-ACCOUNT_NO=" . $customer['ACCOUNT_NO'] . ',code='. $info_data['code'] . ',msg=' . $info_data['msg'] );
                EC::fail($info_data['code']);
            }
            $ACCOUNT_NO = $info_data['data'][0]['ACCOUNT_NO'];
        }
    
        $params  = array();
        foreach ([ 'b_user_id', 'seller_name', 'time1', 'time2', 'order_no', 'status','MCH_TRANS_NO',
            'FMS_TRANS_NO', 'seller_name', 'amount1', 'amount2', 'ACCOUNT_NO', 'oppositeAcctName','paybankname','erpfgsdm','txamt1','txamt2'] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        if( 0 == strlen(strval($inout)) ){
            $inout = Request::post('inout');
        }
        if( 0 < strlen(strval($inout)) ){
            $params['debitCreditFlag'] = strval($inout);
        }
        
        $data_cnt = $bcsTrade_model->searchCnt($params);
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
        $data = $bcsTrade_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_list = $data['data'];
        
        //总计
        $TX_AMT_total = 0;
        if(is_array($data_list) && !empty($data_list)){
        	foreach ($data_list as $v_data){        	
        		$TX_AMT_total += $v_data['TX_AMT'];
        	}
        }
        
        
        //对api接口调用数据返回处理
        if(ApiController::isApi()){
        	$api_data = array();
        	$api_data['list'] = $data_list;
        	$api_data['TX_AMT_total'] = $TX_AMT_total;
        	$api_data['current_page'] = $current_page;
        	$api_data['total_page'] = $total_page;
        	$api_data['inout'] = strval($inout);        	
        	EC::success(EC_OK, $api_data);
        }
    
        $entity_list_html = $this->render('bcsTrade_list', array('data_list' => $data_list, 'TX_AMT_total' => $TX_AMT_total, 'current_page' => $current_page, 'total_page' => $total_page,'inout' => strval($inout) ), true);
        if($isIndex) {
            $view_html = $this->render('bcsTrade', array('entity_list_html' => $entity_list_html,'inout' => strval($inout) ), true);
            $this->render('index', array('page_type' => 'bcsTrade', 'bcsTrade_html' => $view_html,'inout' => strval($inout) ));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html ));
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
        
        $bcsTrade_model = $this->model('bcsTrade');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'order_no', 'user_id', 'code', 'time1', 'time2', 'type', 'order_status',
            'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        if(BcsTradeModel::$_export_type_page == $export_type) {
            $data_cnt = $bcsTrade_model->searchCnt($params);
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
        $data = $bcsTrade_model->searchList($params);
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
    
        $bcsTrade_model = $this->model('bcsTrade');
        $user_id = self::getCurrentUserId();
    
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
    
        if(empty($params)){
            Log::error('update params is empty!');
            EC::fail(EC_PAR_BAD);
        }
    
        $data_old = $bcsTrade_model->getInfo($params);
        if(EC_OK != $data_old['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_old['code']);
        }
        $data_obj = $data_old['data'][0];
        if(empty($data_obj)) {
            Log::error('getInfo empty !');
            EC::fail(EC_RED_EMP);
        }
//         if( BcsTradeModel::$_is_delete_true == $data_obj['is_delete'] ) {
//             Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
//             EC::fail(EC_RED_EXP);
//         }
//         if( BcsTradeModel::$_status_waiting != $data_obj['order_status'] ) {
//             Log::error('record status is exception . status=' . $data_obj['order_status']);
//             EC::fail(EC_RED_EXP);
//         }
    
//         $params['order_status'] = BcsTradeModel::$_status_refuse;
        $params['disenabled_timestamp'] = date('Y-m-d H:i:s',time());
    
        Log::notice('changeStatus ==== >>> params=' . json_encode($params) );
        $data = $bcsTrade_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }
    
    protected function getInfo() {
        $bcsTrade_model = $this->model('bcsTrade');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        $params['user_id'] = $user_id;
    
        $data = $bcsTrade_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
    
        $data_info = $data['data'][0];
        $view_html = $this->render('bcsTradeInfo', array('item' => $data_info), true);
        $this->render('index', array('page_type' => 'bcsTrade', 'bcsTrade_html' => $view_html));
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
        $params['order_status'] = BcsTradeModel::$_status_waiting; // 订单交易状态 1-待付款
        $params['pay_timestamp'] = BcsTradeModel::$_empyt_time; // 操作（付款/拒付）时间
        foreach ([ 'code', 'seller_id', 'seller_name', 'seller_conn_name', 'seller_tel', 'seller_comp_phone',
                    'order_no', 'order_timestamp', 'order_goods_name', 'order_goods_size', 'order_goods_type', 'order_goods_price', 'order_goods_count',
                    'order_delivery_addr', 'order_sum_amount' ] as $val ){
            if($$val) $params[$val] = $$val;
        }
    
        if(empty($params)){
            Log::error('create params is empty!');
            EC::fail(EC_PAR_BAD);
        }
        
        $bcsTrade_model = $this->model('bcsTrade');
        $data = $bcsTrade_model->create($params);
        if(EC_OK != $data['code']){
            Log::error('create Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }
    
    private function tradeStatusQuery($isIndex = false){
        if($isIndex) {
            $entity_list_html = $this->render('bcsTradeStatusQuery', array('data_list' => null), true);
            $this->render('index', array('page_type' => 'bcsTradeStatusQuery', 'bcsTradeStatusQuery_html' => $entity_list_html));
        } else {
            $FMS_TRANS_NO = Request::post('FMS_TRANS_NO');
            $FUNC_CODE = Request::post('FUNC_CODE');
            
            if(!$FMS_TRANS_NO || 0 == strlen(strval($FUNC_CODE)) ){
                Log::error('tradeStatusQuery params error!');
                EC::fail(EC_PAR_ERR);
            }
            
            $bcs_data = $this->model('bank')->transactionStatusQuery($FMS_TRANS_NO,$FUNC_CODE);
            Log::notice('tradeStatusQuery----req_data==>>' . var_export($bcs_data, true));
            
            $data_list = $bcs_data['data'];
            if(empty($data_list)){
                $data_list['OLD_RECODE'] = $bcs_data['code'];
                $data_list['OLD_REMSG'] = $bcs_data['msg'];
            }
            $entity_list_html = $this->render('bcsTradeStatusQuery', array('data_list' => $data_list), true);
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
    protected function loadInfo() {
        $id = Request::post('id');
        if(!$id){
            Log::error('loadTradeInfo params error!');
            EC::fail(EC_PAR_ERR);
        }
    
        $code_model = $this->model('bcsTrade');
    
        $data = $code_model->getInfo(array('id'=>$id));
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
    
        $data_info = $data['data'][0];
    
        $MCH_TRANS_NO = $data_info['MCH_TRANS_NO']; // 商户交易流水号
        $FUNC_CODE = '2'; //0-出入金交易，1-冻结解冻交易，2-现货交易
        Log::notice('loadInfo-str---req_data==>> MCH_TRANS_NO=' . $MCH_TRANS_NO);
        $bcs_data = $this->model('bank')->transactionStatusQuery($MCH_TRANS_NO,$FUNC_CODE);
        Log::notice('loadInfo-end---req_data==>>' . var_export($bcs_data, true));
    
        $data = $bcs_data['data'];
        
        if(empty($data)){
            Log::error("loadInfo failed . msg=" . $bcs_data['code'] . $bcs_data['msg']);
            
            $params = array();
            $params['id'] = $id;
            $params['status'] = 2 ;     // 失败
            $params['comment'] = $bcs_data['code'] . $bcs_data['msg'] ;
            Log::notice('bcsTrade--update----params==>>' . var_export($params, true));
            $data_upd = $code_model->update($params);
            if(EC_OK != $data_upd['code']){
                Log::error("update failed . " . $data_upd['code'] );
            }
            
            EC::fail($bcs_data['code'] , $bcs_data['msg']);
        } else {
            $TRANS_STS = $data['TRANS_STS']; // 交易状态 1:交易成功；2：交易失败；3：状态未知；4：未找到交易记录
            $params = array();
            $params['id'] = $id;
            if( 1 == $TRANS_STS){
                $params['status'] = 1 ;     // 成功
                $params['comment'] = '交易成功' ;
            } else if( 2 == $TRANS_STS){
                $params['status'] = 2 ;     // 失败
                $params['comment'] = '交易失败' ;
            } else if( 3 == $TRANS_STS){
                $params['status'] = 3 ;     // 处理中
                $params['comment'] = '交易状态未知' ;
            } else if( 4 == $TRANS_STS){
                $params['status'] = 2 ;     // 失败
                $params['comment'] = '未找到交易记录' ;
            }
            
            Log::notice('bcsTrade--update----params==>>' . var_export($params, true));
            $data_upd = $code_model->update($params);
            if(EC_OK != $data_upd['code']){
                Log::error("update failed . " . $data_upd['code'] );
            }
        }
    
        EC::success(EC_OK, $data);
    }
 
    // 更新浦发银行账户流水信息列表
    protected function spd_loadAccountTradeList() {
        $virtualAcctNo = Request::post('virtualAcctNo');
        Log::notice("spd_loadAccountTradeList-str ===========================>> data-virtualAcctNo = ##" . $virtualAcctNo . "##" );
        if( empty($virtualAcctNo) ) {
            $post_data = getPostStr();
            if(!empty($post_data)){
                $virtualAcctNo = json_decode($post_data,true)['data']['virtualAcctNo'];
            }
        }
        
        $this->spd_loadAccountTradeList_exec($virtualAcctNo);
        Log::notice("spd_loadAccountTradeList-end ===========================>> data-virtualAcctNo = ##" . $virtualAcctNo . "##" );
        EC::success(EC_OK);
    }
    
    public function spd_loadAccountTradeList_exec($virtualAcctNo = NULL){
        Log::notice("spd_loadAccountTradeList_exec-str ===========================>> data-virtualAcctNo = ##" . $virtualAcctNo . "##" );
    	$spdBank_model = $this->model('spdBank');
    	$conf = $this->getConfig('conf');
    	$bcsCustomer_model = $this->model('bcsCustomer');
    	
    	$data_lists = array();
    	if( empty($virtualAcctNo) ) {
    		$params = array();
    		$params['record_bank_type'] = 2;
    		$params['user_id'] = '-2'; // 查询 非‘-1’即已绑定过用户的虚拟账户数据。
    		$data = $bcsCustomer_model->searchList($params, null, null);
    		if(EC_OK != $data['code']){
    			Log::error("searchList bcsCustomer falied .");
    			EC::fail($data['code']);
    		}
//            Log::notice("response-data ========bcsCustomer-list1===================>> data = ##" . json_encode($data) . "##" );
//             exit;
//     		$data_lists = $data['data'];
			if(isset($data['data']) && !empty($data['data'])){
				$data_lists = array_unique(array_column($data['data'], 'ACCOUNT_NO'));
			}
//             Log::notice("response-data ========bcsCustomer-list===================>> data = ##" . json_encode($data_lists) . "##" );
//             exit;
    	} else {
    		$data_lists[] = $virtualAcctNo;
    	}
    	
    	// 循环账号列表
    	foreach($data_lists as $obj ){
    		$ACCOUNT_NO = $obj;
    		Log::notice("response-data-str ===========================>> data-ACCOUNT_NO = ##" . $ACCOUNT_NO . "##" );
//     		continue;
    		
    		$params = array();
    		$params['beginNumber'] = 1;
    		$params['queryNumber'] = 20;
    	
    		$params['virtualAcctNo'] = $ACCOUNT_NO;//'62250806009'; // 虚账号
    		// for test
//     		$params['shareBeginDate'] = '20160401'; // 分摊开始日期
//     		$params['shareEndDate'] = 20160409; // 分摊结束日期
    		$params['shareBeginDate'] = date('Ymd',time()); // 分摊开始日期
    		$params['shareEndDate'] = date('Ymd',time()); // 分摊结束日期
    		$params['jnlSeqNo'] = ''; // 业务流水号 交易流水号
    		$params['summonsNumber'] = ''; // 流水号的组内序号
    		$params['transBeginDate'] = ''; // 交易开始日期 交易流水产生时间
    		$params['transEndDate'] = ''; // 交易结束日期 交易流水结束时间
    	
    		/*
    		 * 查询已更新的流水数据
    		 */
    		$bcsTrade_model = $this->model('bcsTrade');
    		$params_2 = array();
    		$params_2['shareDate1'] = $params['shareBeginDate'];
    		$params_2['shareDate2'] = $params['shareEndDate'];
    		$params_2['ACCOUNT_NO'] = $params['virtualAcctNo'];
    		$data_cnt = $bcsTrade_model->searchCnt($params_2);
    		if(EC_OK != $data_cnt['code']){
    		    Log::error("searchCnt failed . ");
    		    EC::fail($data_cnt['code']);
    		}
    		$cnt = empty($data_cnt['data']) ? 0 : $data_cnt['data'];
    		
    		$totalNumber = 0 ;
    		do {
    			$data = $spdBank_model->queryAccountTransferAmount($params);
    			Log::notice('spd_loadAccountTradeList ==== >>> data=##' . json_encode($data) . "##");
    	
    			$totalNumber = $data['body']['totalNumber'];
    			Log::notice("spd_loadAccountTradeList-->>searchCnt equal . db_cnt=" . $cnt . ',bank_cnt=' . $totalNumber);
    			if( intval($cnt) == intval($totalNumber) ){
    			    break ;
    			}
//     			Log::notice("\r\n\r\n\r\n=============================================================\r\n\r\n\r\n\r\n");
//     			exit;
    			
    			$data_lists = $data['body']['lists']['list'];
    	
    			$this->addAccountTradeList($data_lists);
    			$params['beginNumber'] = $params['beginNumber'] + $params['queryNumber'] ;
    	
    			// 延时2秒执行。
    			sleep(2); // 延时2秒
    		} while ( $totalNumber >= $params['beginNumber']);
    		Log::notice("response-data-end ===========================>> data-ACCOUNT_NO = ##" . $ACCOUNT_NO . "##" );
    	}
     	//exit();
    	return true;
    }
    
    // 交易流水
    private function addAccountTradeList($data_lists = array()){
        if(empty($data_lists)){
            Log::notice("addAccountTradeList data_lists is empty . ");
            return ;
        }
        
//         Log::notice("addAccountTradeList =================111==========>> data-data_lists = ##" . json_encode($data_lists) . "##" );
        if(!empty($data_lists['acctNo'])) {
            $data_lists_temp = array();
            $data_lists_temp[] = $data_lists;
//             Log::notice("addAccountTradeList =================222==========>> data-data_lists = ##" . json_encode($data_lists_temp) . "##" );
            $data_lists = $data_lists_temp;
        }
//         Log::notice("addAccountTradeList =================333==========>> data-data_lists = ##" . json_encode($data_lists) . "##" );
//         exit;

        $bcsTrade_model = $this->model('bcsTrade');
        $bcsCustomer_model = $this->model('bcsCustomer');
        $data = $bcsCustomer_model->searchList();
        $bcsCustomerInfo = array();
        //增加bcsCustomer表中虚拟账号和分公司的对应关系
        if(isset($data['data']) && is_array($data['data'])) {
            foreach($data['data'] as $key => $value) {
                if(!array_key_exists($value['ACCOUNT_NO'],$bcsCustomerInfo) && !empty($value['user_fgs_dm'])) {
                    $bcsCustomerInfo[$value['ACCOUNT_NO']] = $value['user_fgs_dm'];
                }
            }
        }

        foreach($data_lists as $obj ){
            Log::notice("addAccountTradeList ===========================>> data = ##" . json_encode($obj) . "##" );
            $trade = array();
            $trade['record_bank_type'] = 2; // 1-bcs长沙银行 2-psd浦发银行
            $trade['ACCOUNT_NO'] = $obj['virtualAcctNo']; // 虚账号
            $trade['MCH_TRANS_NO'] = $obj['tellerJnlNo']; // 柜员流水号
            $trade['accountBalance'] = $obj['accountBalance']; // 帐户余额
            
            $info_data = $bcsTrade_model->getInfo($trade);
            if(EC_OK != $info_data['code']){
                Log::error("getInfo failed . virtualAcctNo-ACCOUNT_NO=" . $trade['ACCOUNT_NO'] . ',code='. $info_data['code'] . ',msg=' . $info_data['msg'] );
                continue;
            }
//             Log::notice("addAccountTradeList ================122==========>> data = ##" . json_encode($info_data) . "##" );
//             exit;
    
            // 8924账户明细查询
//             Log::notice("addAccountTradeList ================199-1==========>> data = ##" . json_encode($obj) . "##" );
            $this->queryTradeSerial($obj);
//             Log::notice("addAccountTradeList ================199-2==========>> data = ##" . json_encode($obj) . "##" );
//             exit;
            
            $trade['SELLER_SIT_NO'] = $obj['virtualAcctName']; // 虚账户名称
            $trade['debitCreditFlag'] = $obj['debitCreditFlag']; // 借贷标志
            $trade['TX_AMT'] = $obj['transAmount']; // 交易金额
            $trade['shareDate'] = $obj['shareDate']; // 分摊日期
            $transTime = (6 == strlen( strval($obj['transTime']) ) ) ? strval($obj['transTime']) : "0" . strval($obj['transTime']);
            $trade['TRANS_TIME'] = date("Y-m-d H:i:s",strtotime( strval($obj['transDate']) . $transTime ) ); // 交易日期 +交易时间
            $trade['comment'] = $obj['summaryCode']; // 摘要代码
            $trade['oppositeAcctNo'] = $obj['oppositeAcctNo']; // 对方帐号
            $trade['oppositeAcctName'] = $obj['oppositeAcctName']; // 对方名称
            $trade['status'] = 1; // 交易发送状态 1-成功 2-失败 3-未知
            $trade['payeeBankNo'] = $obj['payeeBankNo']; // 对方行号
            $trade['payeeBankName'] = $obj['payeeBankName']; // 对方行名
            //分公司字段值
            $trade['erp_fgsdm']    = array_key_exists($obj['virtualAcctNo'],$bcsCustomerInfo)?$bcsCustomerInfo[$obj['virtualAcctNo']]:'';

            $info_data = $info_data['data'][0];
            if( !empty($info_data) ){
                $trade['id'] = $info_data['id'];
                $upd_data = $bcsTrade_model->update($trade);
                if(EC_OK != $upd_data['code']){
                    Log::error("getInfo failed . virtualAcctNo-ACCOUNT_NO=" . $trade['ACCOUNT_NO'] . ',code='. $upd_data['code'] . ',msg=' . $upd_data['msg'] );
                    continue;
                }
                Log::notice('addAccountTradeList ==== >>> upd-data-end=##' . $obj['virtualAcctName'] . "##");
            } else {
                $data_rs = $bcsTrade_model->create_add($trade);
                
                
                if( 1 == $trade['debitCreditFlag']){
                	//对收款进行短信发送
                	SmsController::sendSmsCodeForCollection($trade['ACCOUNT_NO'], $trade['oppositeAcctName'], $trade['TX_AMT'], $trade['oppositeAcctNo']);
                
                	//收款单同步erp
                	$this->erp_syncBillsOfCollection($trade['MCH_TRANS_NO']);
                }
                
                if($data_rs['code'] !== EC_OK){
                    Log::error('addAccountTradeList . create bcsCustomer error . code='. $data_rs['code'] . ',msg=' . $data_rs['msg'] );
                    continue;
                }                               
                
                Log::notice('addAccountTradeList ==== >>> add-data-end=##' . $obj['virtualAcctName'] . "##");
            }
        }
    }

    public function queryTradeSerial( &$obj ){
//         Log::notice("queryTradeSerial-str ===========================>> data-obj = ##" . json_encode($obj) . "##" );
//         if( !empty($obj['oppositeAcctNo']) && !empty($obj['oppositeAcctName']) ){
//             Log::notice("queryAccountTrade oppositeAcct info is fill . ");
//             return ;
//         }
        
        $spdBank_model = $this->model('spdBank');
        
        $params = array();
        $params['beginNumber'] = 1;
        $params['queryNumber'] = 20;
        $params['beginDate'] = $obj['transDate']; // 开始日期
        $params['endDate'] = $obj['transDate']; // 结束日期
        $params['transAmount'] = $obj['transAmount']; // 交易金额
        $params['subAccount'] = $obj['oppositeAcctNo']; // 对方帐号
        $params['subAcctName'] = $obj['oppositeAcctName']; // 对方户名
        
        $data = $spdBank_model->queryAccountTrade($params);
        if(EC_OK != $data['code']){
            Log::error("spd-queryAccountTrade falied .");
            return ;
        }
        
        $data_lists = $data['body']['lists']['list'];
        if(empty($data_lists)){
            Log::notice("queryAccountTrade data_lists is empty . ");
            return ;
        }
//         Log::notice("response-data ==============531=============>> data-data_lists = ##" . json_encode($data_lists) . "##" );
//         exit;
        
        if(!empty($data_lists['seqNo'])) {
            $data_lists_temp = array();
            $data_lists_temp[] = $data_lists;
            $data_lists = $data_lists_temp;
        }
//         Log::notice("response-data ==============532=============>> data-data_lists = ##" . json_encode($data_lists) . "##" );
        
        $tellerJnlNo = $obj['tellerJnlNo']; // 柜员流水号
        Log::notice("queryAccountTrade=============>>> tellerJnlNo=" . $tellerJnlNo);
        foreach($data_lists as $objVal ){
            Log::notice("queryAccountTrade=============>>> seqNo=" . $objVal['seqNo']);
            if( $tellerJnlNo == $objVal['seqNo'] ){
                $obj['oppositeAcctNo'] = $objVal['payeeAcctNo']; // 对方账号
                $obj['oppositeAcctName'] = $objVal['payeeName']; // 对方户名
                $obj['payeeBankNo'] = $objVal['payeeBankNo']; // 对方行号
                $obj['payeeBankName'] = $objVal['payeeBankName']; // 对方行名
                break ;
            }
        }
        
    }
    
    //收款单同步erp $MCH_TRANS_NO流水号
    public function erp_syncBillsOfCollection($mch_trans_no = NULL, $is_ec = 0){
    	
    	$mch_trans_no = ($mch_trans_no == NULL) ? Request::post('mch_trans_no') : $mch_trans_no;    	 
    	$is_ec = ($is_ec == 0) ? intval(Request::post('is_ec')) : intval($is_ec);
    	Log::skdNotice("erp_syncBillsOfCollection ===>> mch_trans_no=" .$mch_trans_no ." is_ec=".$is_ec);
    	
    	if(empty($mch_trans_no)){
    		Log::skdError('mch_trans_no empty !');
    		if($is_ec) EC::fail(EC_PAR_ERR);
    		return false;
    	}
    	 
    	//根据流水号查流水的数据
    	$bcsTrade_model = $this->model('bcsTrade');
    	$data = $bcsTrade_model->getInfo(array('debitCreditFlag' => 1, 'MCH_TRANS_NO' => $mch_trans_no));
    	if(empty($data) || !is_array($data) || EC_OK != $data['code'] || !isset($data['data'])) {
    		Log::skdError('bcsTrade getInfo empty !');
    		if($is_ec) EC::fail(EC_DAT_NON);
    		return false;
    	}
    	$data = $data['data'][0];
    	if(empty($data)) {
    		Log::skdError('bcsTrade getInfo empty !');
    		if($is_ec) EC::fail(EC_RED_EMP);
    		return false;
    	}
    	//必须为收款
    	if(1 != $data['debitCreditFlag']){
    		Log::skdError('the bcsTrade debitCreditFlag is' . $data['debitCreditFlag'] . '!');
    		if($is_ec) EC::fail(EC_RED_EXP);
    		return false;
    	}
    	
    	//判断是否已同步erp
    	if(2 == $data['is_erp_sync'] || 4 == $data['is_erp_sync']){
    		Log::skdError("the bcsTrade has been sync erp: is_erp_sync={$data['is_erp_sync']}!");
    		if($is_ec) EC::fail(EC_REC_EST);
    		return false;
    	}
    	 
    	//查合伙人信息
    	$params  = array();
    	$params['ACCOUNT_NO'] = $data['ACCOUNT_NO'];
    	$bcsCustomer_model = $this->model('bcsCustomer');
    	$bcs_data = $bcsCustomer_model->getInfo($params);
    	//Log::write("bcs_data==".var_export($bcs_data, true), 'debug', 'debug-'.date('Y-m-d'));
    	if(EC_OK != $bcs_data['code'] || !is_array($bcs_data) || !isset($bcs_data['data'])){
    		Log::skdError("bcsCustomer getInfo failed . ");
    		if($is_ec) EC::fail(EC_USR_NON);
    		return false;
    	}
    	$bcs_data = $bcs_data['data'][0];
    	if(empty($bcs_data)) {
    		Log::skdError('bcsCustomer getInfo empty !');
    		if($is_ec) EC::fail(EC_RED_EMP);
    		return false;
    	}
    	
    	//$data['oppositeAcctName'] = '瑞安市奥利达标准件制造有限公司';
    	Log::skdNotice(" 单位名称 oppositeAcctName = " . $data['oppositeAcctName']);
    	
    	if(empty($data['oppositeAcctName'])){
    		Log::skdError('oppositeAcctName is empty!' );
    		if($is_ec) EC::fail(EC_ERR, '失败：单位名称为空！');
    		return false;
    	}
    	
    	if('大汉电子商务有限公司' == trim($data['oppositeAcctName'])){
    		Log::skdNotice('oppositeAcctName=' .$data['oppositeAcctName']. ', do not need to be synchronized');
    		
    		//修改同步状态
    		$up_params = array();
    		$up_params['id'] = $data['id'];
    		$up_params['is_erp_sync'] = 4; //付款单是否同步 1否 2同步成功 3同步失败 4不需同步
    		$up_params['erp_sync_timestamp'] = date('Y-m-d H:i:s',time());
    		$bt_data = $bcsTrade_model->update($up_params);
    		if(EC_OK != $bt_data['code']){
    			Log::skdError('update bcsTrade is_erp_sync status fail!');
    			if($is_ec) EC::fail($bt_data['code']);
    			return false;
    		}    		
    		
    		if($is_ec) EC::success(EC_OK);
    		return true;
    	}
    	
    	//根据对方付款单位名称调用erp接口查该单位代码
    	$ct_params = array();
    	$ct_params['dwmc'] = $data['oppositeAcctName']; 
    	$user_model = $this->model('user');
    	$ct_info_data = $user_model->erp_getContactCompanyList($ct_params);
    	if(EC_OK_ERP != $ct_info_data['code']){
    		Log::skdError('erp_getContactCompanyList Fail!' . $ct_info_data['msg']);
    		if($is_ec) EC::fail($ct_info_data['code'], $ct_info_data['msg']);
    		return false;
    	}
    	$ct_info_data = $ct_info_data['data']['data'];
    	$dwdm = '';
    	if(!empty($ct_info_data) && isset($ct_info_data[0])){
    		$dwdm = $ct_info_data[0]['dwdm'];
    	}
    	if(empty($dwdm)){
    		Log::skdError('erp_getContactCompanyList Fail： dwdm is empty!' );
    		if($is_ec) EC::fail(EC_ERR, '失败：单位代码为空！');
    		return false;
    	}
    	
    	/*
    	 "head":{
    	"dwmc":"某某单位",
    	"rq":"2016-5-1",
    	"je":1000,
    	"usercode":"000017",
    	"gszh":"43001539061052504886",
    	"gskhh":"中国建设银行",
    	"zh":"800222699208866",
    	"khh":"广州银行东圃支行"
    	},
    	"details":[{
    	"xh":"1",
    	"je":"11.8"
    	}]
    	*/
    	
    	
    	$head = array();
    	$head['dwdm'] = $dwdm; //单位名称
    	$head['rq'] = $data['TRANS_TIME']; //日期
    	$head['je'] = $data['TX_AMT']; //金额
    	$head['usercode'] = $bcs_data['user_id'];
    	$head['gszh'] = $data['ACCOUNT_NO']; //收款公司账号
    	$head['gskhh'] = '上海浦东发展银行'; //收款开户行名称
    	$head['zh'] = $data['oppositeAcctNo']; //付款账号
    	$head['khh'] = $data['payeeBankName']; //付款开户行名称
    	 
    	$details = array();
    	$details['xh'] = $data['id']; //序号
    	$details['je'] = $data['TX_AMT']; //金额
    	 
    	$params = array();
    	$params['head'] = $head;
    	$params['details'][] = $details;
    
    	Log::skdNotice("request-data ============>> params = ##" . json_encode($params) . "##" );    	    	
    	$is_erp_sync = 2;
    	$bcsTrade_model = $this->model('bcsTrade');
    	$res_data = $bcsTrade_model->erp_syncBillsOfCollection($params);
    	if(EC_OK_ERP != $res_data['code']){
    		Log::skdError('erp_syncBillsOfCollection Fail:'.$res_data['msg']);
    		//EC::fail($res_data['code'], $res_data['msg']);
    		//return false;
    		$is_erp_sync = 3;
    	}
    	Log::skdNotice("response-data ============>> res_data = ##" . json_encode($res_data) . "##" );
    	 
    	//修改同步状态
    	$up_params = array();
    	$up_params['id'] = $data['id'];
    	$up_params['is_erp_sync'] = $is_erp_sync; //付款单是否同步 1否 2同步成功 3同步失败
    	$up_params['erp_sync_timestamp'] = date('Y-m-d H:i:s',time());
    	$bt_data = $bcsTrade_model->update($up_params);
    	if(EC_OK != $bt_data['code']){
    		Log::skdError('update bcsTrade is_erp_sync status fail!');
    		if($is_ec) EC::fail($bt_data['code']);
    		return false;
    	}
    	
    	if(3 == $is_erp_sync){
    		if($is_ec) EC::fail($res_data['code'], $res_data['msg']);
    		return false;
    	}
    	    	
    	if($is_ec) EC::success(EC_OK);
    	return true;
    }
    
}