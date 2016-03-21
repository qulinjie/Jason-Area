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
    
    protected function searchList($isIndex = false) {
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
        foreach ([ 'b_user_id', 'seller_name', 'time1', 'time2', 'order_no', 'status',
            'FMS_TRANS_NO', 'seller_name', 'amount1', 'amount2', 'ACCOUNT_NO'] as $val){
            if($$val) $params[$val] = $$val;
        }
    
//         $params['debitCreditFlag'] = strval($inout);
        
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
    
        $entity_list_html = $this->render('bcsTrade_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('bcsTrade', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'bcsTrade', 'bcsTrade_html' => $view_html));
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
            
            EC::fail($bcs_data['code'] . $bcs_data['msg']);
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
 
    protected function spd_loadAccountTradeList() {
        $virtualAcctNo = Request::post('virtualAcctNo');
    
        $spdBank_model = $this->model('spdBank');
        $conf = $this->getConfig('conf');
    
        $params = array();
        $params['beginNumber'] = 1;
        $params['queryNumber'] = 20;
        
        //TODO for test
        $params['virtualAcctNo'] = '62250806009'; // 虚账号
        $params['shareBeginDate'] = '20160301'; // 分摊开始日期
//         $params['shareBeginDate'] = date('Ymd',time()); // 分摊开始日期
        $params['shareEndDate'] = date('Ymd',time()); // 分摊结束日期
        $params['jnlSeqNo'] = ''; // 业务流水号 交易流水号
        $params['summonsNumber'] = ''; // 流水号的组内序号
        $params['transBeginDate'] = ''; // 交易开始日期 交易流水产生时间
        $params['transEndDate'] = ''; // 交易结束日期 交易流水结束时间
        
        if( !empty($virtualAcctNo) ) {
            $params['virtualAcctNo'] = $virtualAcctNo;
        }
    
        $totalNumber = 0 ;
        do {
            $data = $spdBank_model->queryAccountTransferAmount($params);
            Log::notice('spd_loadAccountTradeList ==== >>> data=##' . json_encode($data) . "##");
    
            $totalNumber = $data['body']['totalNumber'];
            $data_lists = $data['body']['lists']['list'];
    
            $this->addAccountTradeList($data_lists);
            $params['beginNumber'] = $params['beginNumber'] + $params['queryNumber'] ;
        } while ( $totalNumber >= $params['beginNumber']);
    
        EC::success(EC_OK);
    }
    
    private function addAccountTradeList($data_lists = array()){
        if(empty($data_lists)){
            Log::notice("addAccountTradeList data_lists is empty . ");
            return ;
        }
    
        $bcsTrade_model = $this->model('bcsTrade');
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
    
            $trade['SELLER_SIT_NO'] = $obj['virtualAcctName']; // 虚账户名称
            $trade['debitCreditFlag'] = $obj['debitCreditFlag']; // 借贷标志
            $trade['TX_AMT'] = $obj['transAmount']; // 交易金额
            $trade['shareDate'] = $obj['shareDate']; // 分摊日期
            $transTime = (6 == strlen( strval($obj['transTime']) ) ) ? strval($obj['transTime']) : "0" . strval($obj['transTime']);
            $trade['TRANS_TIME'] = date("Y-m-d h:i:s",strtotime( strval($obj['transDate']) . $transTime ) ); // 交易日期 +交易时间
            $trade['comment'] = $obj['summaryCode']; // 摘要代码
            $trade['oppositeAcctNo'] = $obj['oppositeAcctNo']; // 对方帐号
            $trade['oppositeAcctName'] = $obj['oppositeAcctName']; // 对方名称
            $trade['status'] = 1; // 交易发送状态 1-成功 2-失败 3-未知
    
            $info_data = $info_data['data'][0];
            if( !empty($info_data) ){
                $trade['id'] = $info_data['id'];
                $upd_data = $bcsTrade_model->update($trade);
                if(EC_OK != $upd_data['code']){
                    Log::error("getInfo failed . virtualAcctNo-ACCOUNT_NO=" . $trade['ACCOUNT_NO'] . ',code='. $upd_data['code'] . ',msg=' . $upd_data['msg'] );
                    continue;
                }
            } else {
                $data_rs = $bcsTrade_model->create_add($trade);
                if($data_rs['code'] !== EC_OK){
                    Log::error('addAccountTradeList . create bcsCustomer error . code='. $data_rs['code'] . ',msg=' . $data_rs['msg'] );
                }
            }
            Log::notice('addAccountTradeList ==== >>> add-data=##' . $obj['virtualAcctName'] . "##");
        }
    }
    
}