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
                case 'searchListFrist':
                    $this->searchList(true, '1');
                   	break;
                case 'searchListSecond':
                    $this->searchList(true, '2');
                    break;
                    // str-收款单
                case 'getIndexBill':
                    $this->searchListBill(true);
                    break;
                case 'searchListBill':
                    $this->searchListBill();
                    break;
                case 'registerNet':
                    $this->registerNet();
                    break;
                    // end-收款单
                    
                case 'getInfo':
                    $this->getInfo();
                    break;                
                case 'getInfoCheck':
                    $this->getInfo(true);
                    break;
                case 'getOneTrandRecord':
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
                    
                case 'createApply':
                    $this->createApply();
                    break;
                case 'createAdvanceApply':
                	$this->createApply('1');
                	break;    
                case 'create_add':
                    $this->create_add();
                    break;
                case 'checkBankName':
                    $this->checkBankName();
                    break;
                    
                case 'erp_getOrderBuy':
                    $this->erp_getOrderBuy();
                    break;
                case 'erp_getOrderBuyInfo':
                    $this->erp_getOrderBuyInfo();
                    break;  
                case 'erp_getSellOrderList':
                    $this->erp_getSellOrderList();
                    break;
                case 'erp_getSellOrderInfo':
                    $this->erp_getSellOrderInfo();
                    break;
                case 'erp_getOrgNameInfo':
                    $this->erp_getOrgNameInfo();
                    break;
                case 'erp_syncBillsOfPayment':
                	$this->erp_syncBillsOfPayment();
                    break;
                case 'test_sendTransferTrade':
                    $this->test_sendTransferTrade();
                    break;

                case 'auditOneTradRecord':
                    $this->auditOneTradRecord();
                    break;
                case 'sendTransferTrade':
                	$this->sendTransferTrade();
                	break; 
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }
    
    protected function searchList($isIndex = false, $audit_level='0') {
        $current_page = Request::post('page');
        $order_no = Request::post('order_no');
        $code = Request::post('code');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $type = Request::post('type');
        $order_status = Request::post('order_status');
        $apply_status = Request::post('apply_status');
        $order_time1 = Request::post('order_time1');
        $order_time2 = Request::post('order_time2');
        $seller_name = Request::post('seller_name');
        $seller_conn_name = Request::post('seller_conn_name');
        $order_sum_amount1 = Request::post('order_sum_amount1');
        $order_sum_amount2 = Request::post('order_sum_amount2');        
        $backhost_status = Request::post('backhost_status');
        
        $apply_status = $apply_status == 4 ? 2 : $apply_status;
        $audit_level = $audit_level != '0' ? $audit_level : Request::post('audit_level');
        
        $tradeRecord_model = $this->model('tradeRecord');
        $user_id = self::getCurrentUserId();
    
//         if($isIndex && !$order_status) {
//             $order_status = TradeRecordModel::$_status_waiting;
//         }

        $bcsCustomer_model = $this->model('bcsCustomer');
        $params  = array();
        $params['user_id'] = $user_id;
        $data = $bcsCustomer_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
        $data_info = $data['data'][0];
        $ACCOUNT_NO = $data_info['ACCOUNT_NO'];
        
        $params  = array();
        foreach ([ 'order_no', 'user_id', 'audit_user_id_first', 'audit_user_id_second', 'code', 'time1', 'time2', 'type', 'order_status', 'apply_status',
                    'backhost_status', 'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2',
                    'ACCOUNT_NO'
                ] as $val)
        {
            if($$val) $params[$val] = $$val;
        }   

        $is_admin = AdminController::isAdmin();
        //非管理员必须要求user_id
        if(!$is_admin){
        	if(!isset($params['user_id'])){
        		Log::error("searchList failed . ");
        		EC::fail(EC_PAR_ERR);
        	}
        	if('1' == strval($audit_level)){
        		//一级审核
        		$params['audit_user_id_first'] =  $params['user_id'];
        		unset($params['user_id']);
        	}
        }else{
        	if ('2' == strval($audit_level)){
        		//二级审核
        		$params['audit_user_id_second'] = $params['user_id'];
        	    if(isset($params['user_id'])){
        		    unset($params['user_id']);
        	    }
        	}
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
        
        $data_list = $data['data'] ? $data['data'] : [];
//        $tradeRecordItem_model = $this->model('tradeRecordItem');
        
//         foreach ($data_list as $key => $val){
//             $data = $tradeRecordItem_model->searchList(array('trade_record_id' => $val['id']));
//             if($data['code'] !== EC_OK){
//                 Log::error('tradeRecordItem searchList error');
//             }            
//             $data_list[$key]['list'] = $data['data'] ? $data['data'] : [];
//         }
        
        //加入总计
        $order_bid_amount_total = 0;
        foreach ($data_list as $v_data){
        	$order_bid_amount_total += $v_data['order_bid_amount'];
        }
        
        $entity_list_html = $this->render('tradeRecord_list', array('is_admin' => $is_admin, 'order_bid_amount_total' => $order_bid_amount_total, 'current_user_id' => $user_id, 'data_list' => $data_list, 'current_page' => $current_page, 'audit_level' => $audit_level, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('tradeRecord', array('is_admin' => $is_admin, 'current_user_id' => $user_id, 'audit_level' => $audit_level, 'entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'tradeRecord', 'tradeRecord_html' => $view_html, 'bcsCustomerInfo' => $data_info, 'audit_level' => $audit_level) );
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
    protected function searchListBill($isIndex = false) {
        $current_page = Request::post('page');
        $order_no = Request::post('order_no');
        $code = Request::post('code');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $type = Request::post('type');
        $order_time1 = Request::post('order_time1');
        $order_time2 = Request::post('order_time2');
        $seller_name = Request::post('seller_name');
        $seller_conn_name = Request::post('seller_conn_name');
        $order_sum_amount1 = Request::post('order_sum_amount1');
        $order_sum_amount2 = Request::post('order_sum_amount2');
    
        $tradeRecord_model = $this->model('tradeRecord');
        $user_id = self::getCurrentUserId();
    
        $order_status = TradeRecordModel::$_status_paid;
    
        $params  = array();
        foreach ([ 'order_no', 'code', 'time1', 'time2', 'type', 'order_status',
            'order_time1', 'order_time2', 'seller_name', 'seller_conn_name', 'order_sum_amount1', 'order_sum_amount2' ] as $val){
            if($$val) $params[$val] = $$val;
        }
        $params['seller_id'] = $user_id;
    
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
    
        $data_list = $data['data'] ? $data['data'] : [];
        
        $tradeRecordItem_model = $this->model('tradeRecordItem');
        
        foreach ($data_list as $key => $val){
            $data = $tradeRecordItem_model->searchList(array('trade_record_id' => $val['id']));
            if($data['code'] !== EC_OK){
                Log::error('tradeRecordItem searchList error');
            }
            $data_list[$key]['list'] = $data['data'] ? $data['data'] : [];
        }
        
        $entity_list_html = $this->render('tradeRecordBill_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
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
    
            $view_html = $this->render('tradeRecordBill', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'tradeRecordBill', 'tradeRecordBill_html' => $view_html, 'bcsCustomerInfo' => $data_info) );
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
    protected function registerNet(){
        if(!$postData = $this->post('data')){
            Log::error('registerNet params error');
            EC::fail(EC_PAR_BAD);
        }

        $postData = explode(';', $postData);
        array_pop($postData);      
        $tradeRecordItemModel = $this->model('tradeRecordItem');
        $sendData = array();
        foreach ($postData as $val){
            //0:order_no,1:id , 2:item_no, 3:price,4:number,5:weight.  吨数是总吨数
            list($order_no,$id,$item_no,$price,$number,$weight) = explode('_', $val);
            $data = $tradeRecordItemModel->update(array('id' => $id,'item_count_send' => $number,'item_weight_send' => $weight,'item_amount_send' => $weight*$price));
            if($data['code'] !== EC_OK){
                Log::error('tradeRecordItem update error code='.$data['code']);   
                EC::fail($data['code']);
            }
            $sendData [] = array('item_no' => $item_no,'number' => $number,'weight' => $weight);
        }
        
        //更新订单实提状态
        if(isset($order_no)){
            $data = $this->model('tradeRecord')->searchList(array('order_no' => $order_no));
            if($data['code'] !== EC_OK){
                Log::error('registerNet update tradeRecord status error');
                EC::fail(EC_UPD_FAI);
            }else{           
                $params = array('check_status' => TradeRecordModel::$_check_status_y,'id' => $data['data'][0]['id'],'user_id' => $data['data'][0]['user_id'],'check_timestamp' => date('Y-m-d H:i:s'));
                $data = $this->model('tradeRecord')->update($params);
                if($data['code'] !== EC_OK){
                    Log::error('registerNet update tradeRecord status error');
                    EC::fail(EC_UPD_FAI);
                }
            }
        }
       
        $data = $this->model('tradeRecordItem')->registerNetToServer(array('data' => $sendData));
        if($data['code'] !== EC_OK){
            Log::error('registerNet send server error code='.$data['code']);
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
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
        $params['seller_id'] = $user_id;
    
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
        Log::error('data_obj<<<<<<<<'.var_export($data_obj,true));
        if(empty($data_obj)) {
            Log::error('getInfo empty !');
            EC::fail(EC_RED_EMP);
        }
        if( TradeRecordModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
        if( TradeRecordModel::$_status_waiting == $data_obj['order_status'] ) {
            Log::error('record status is exception . status=' . $data_obj['order_status']);
            EC::fail(EC_RED_EXP);
        }
    
        $params['user_id'] = $data_obj['user_id'];
        $params['send_status'] = TradeRecordModel::$_send_status_y;
        $params['send_timestamp'] = date('Y-m-d H:i:s',time());
    
        Log::notice('changeStatus ==== >>> params=' . json_encode($params) );
        $data = $tradeRecord_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update Fail!');
            EC::fail($data['code']);
        } 
        
        $data = $tradeRecord_model->orderStatusToServer(array('data' => array('order_no' => $data_obj['order_no'])));
        if($data['code'] !== EC_OK){
            Log::error('orderStatusToServer error code='.$data['code']);
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
    
    protected function getInfo($isCheck=false) {
        $id = Request::post('id');
        $audit_level = Request::post('audit_level');
    
        $tradeRecord_model = $this->model('tradeRecord');
        $tradeRecordItem_model = $this->model('tradeRecordItem');        
    
        $params  = array();
        $params['id'] = $id;

        $is_admin = AdminController::isAdmin();
        $user_id = self::getCurrentUserId();
        $mobile = '';
        if(!$is_admin){        	
	        if($isCheck){
	            $params['seller_id'] = $user_id;
	        } else {
	            $params['user_id'] = $user_id;
	            if('1' == strval($audit_level)){
	            	//一级审核
	            	$params['audit_user_id_first'] =  $user_id;
	            	if(isset($params['user_id']))
	            	    unset($params['user_id']);
	            	
	            	$mobile = UserController::getUserMobileByUserId($user_id); 	            	
	            }
	        }
        }else {
        	if ('2' == strval($audit_level)){
        		//二级审核
        		$params['audit_user_id_second'] = $user_id; 
        		if(isset($params['user_id']))
        			unset($params['user_id']);
        	}
        }        
           
        $data = $tradeRecord_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
        $data_info = $data['data'][0];
        
        $data_item = $tradeRecordItem_model->searchList(array('trade_record_id' => $data_info['id']));
        if(EC_OK != $data_item['code']){
            Log::error("searchList failed . ");
            EC::fail($data_item['code']);
        }
        $data_info['data_list'] = $data_item['data'];
        //Log::notice('data_info-----------------------------------params==>>' . var_export($data_info, true));
        $is_tradeRecordAudit = false;
        if(!$is_admin){        	
	        if($isCheck){
	            $entity_list_html = $this->render('tradeCheck', array('item' => $data_info), true);
	            EC::success(EC_OK, array('tradeRecord_check' => $entity_list_html));
	        }elseif ('1' == strval($audit_level)){
	        	$is_tradeRecordAudit = true;
	        }elseif(intval($data_info['user_id']) == intval($user_id)){
        		$is_tradeRecordAudit = true;
        	}else {
	            $entity_list_html = $this->render('tradePay', array('item' => $data_info), true);
	            EC::success(EC_OK, array('tradeRecord_pay' => $entity_list_html));
	        }
        }else{
        	$is_tradeRecordAudit = true;        	
        }
        if($is_tradeRecordAudit){
        	//用于付款审批、查看
        	$entity_list_html = $this->render('tradeRecordAudit', array('data_info' => $data_info, 'mobile' => $mobile, 'is_admin' => $is_admin, 'audit_level' => $audit_level), true);
        	EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
    private function create(){
        $post_data = self::getPostDataJson();
        if(empty($post_data)) {
            Log::error('post_data params empty error!');
            EC::fail(EC_PAR_ERR);
        }
        // request 数据
        $request_data = $post_data['data'];
        
        $order = $request_data['order']; // 订单信息
        $orderItem = $request_data['orderItem']; // 订单商品信息
    
        if(!$order || !$orderItem || !$order['order_num']){
            Log::error('create params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        //获取合伙人ID
        $partner_info = $this->model('user')->getList(array('user_type' => 2,'account' => $order['tel'] ,'fields' => array('id')));
        if($partner_info['code'] !== EC_OK || !$partner_info['data']){
            Log::error('create order  partner not exist!');
            EC::fail(EC_PAR_ERR);
        }        
        $pay_user_id  = $partner_info['data'][0]['id'];
        
        $trade_record = array();
        $trade_record_item = array();
        $item_bid_amount = array();
        $trade_record_amount = array();
        foreach ($orderItem as $itemVal){
            $record_item = array();
            
            $record_item['order_no'] = $order['order_num']; // 订单号
            
            $record_item['itme_no'] = $itemVal['item_num']; // 订单商品ID
            $record_item['item_name'] = $itemVal['product_name']; // 品名 
            $record_item['item_type'] = $itemVal['material_name']; // 材质
            $record_item['item_size'] = $itemVal['size_name']; // 规格
            $record_item['item_factory'] = $itemVal['factory_name']; // 钢厂
            $record_item['item_count'] = $itemVal['quantity']; // 数量
            $record_item['item_weight'] = $itemVal['allton']; // 总重量
            $record_item['item_price'] = $itemVal['price']; // 商品单价
            $record_item['bid_price'] = $itemVal['pur_price']; // 采购单价
            $record_item['item_delivery_addr'] = $itemVal['delivery_point']; // 交割地点（仓库地址）
            $record_item['item_amount'] = $itemVal['amount']; // 总金额
            $record_item['bid_amount'] = floatval($itemVal['pur_price']) * floatval($itemVal['allton']); // 采购总金额 = 采购单价 * 总重量
            
            // 采购单位
            $trade_record_item[$itemVal['pur_unit']][] = $record_item;
            // 采购金额
            if(empty($item_bid_amount[$itemVal['pur_unit']])){
                $item_bid_amount[$itemVal['pur_unit']] = floatval($record_item['bid_amount']);
            } else {
                $item_bid_amount[$itemVal['pur_unit']] =  floatval($item_bid_amount[$itemVal['pur_unit']]) + floatval($record_item['bid_amount']) ;
            }
            // 拆分后订单的金额
            if(empty($trade_record_amount[$itemVal['pur_unit']])){
                $trade_record_amount[$itemVal['pur_unit']] = floatval($record_item['item_amount']);
            } else {
                $trade_record_amount[$itemVal['pur_unit']] =  floatval($item_bid_amount[$itemVal['pur_unit']]) + floatval($record_item['item_amount']) ;
            }
        }
        
//         Log::error('----------------------------------trade_record_item------------------------------params==>>' . var_export($trade_record_item, true));
        // 商户付款ID（大汉账号） 跟合伙人电话一职
        $user_model = $this->model('user');
        
        foreach ($trade_record_item as $itemKey => $itemVal){
            $trade_record[$itemKey] = array();
            
            $trade_record[$itemKey]['user_id'] = $pay_user_id; // 付款用户ID（支付账户）
            
            $trade_record[$itemKey]['seller_name'] = $itemKey; // 卖家/供应商公司名称
            
            // 查询卖家支付账户的用户ID
            $data = $user_model->getInfo(array('company_name' => $itemKey));
            if(EC_OK != $data['code']){
                Log::error("getInfo failed . company_name=" . $itemKey);
            } else {
                $data_info = $data['data'][0];
                $trade_record[$itemKey]['seller_id'] = $data_info['id']; // 卖家/供应商ID（支付账户）
            }
            
            $trade_record[$itemKey]['order_timestamp'] = $order['order_date']; // 订单日期
            $trade_record[$itemKey]['order_no'] = $order['order_num']; // 订单号
            $trade_record[$itemKey]['partner_name'] = $order['name']; // 合伙人名字
            $trade_record[$itemKey]['partner_tel'] = $order['tel']; // 合伙人电话
            $trade_record[$itemKey]['partner_company_tel'] = $order['com_tel']; // 合伙人公司电话
            $trade_record[$itemKey]['partner_company_name'] = $order['invoice_unit']; // 合伙人公司
            $trade_record[$itemKey]['order_amount'] = $order['order_amount']; // 订单总金额（元）
            
            $trade_record[$itemKey]['item'] = $trade_record_item[$itemKey]; // 商品列表
            
            $trade_record[$itemKey]['order_bid_amount'] = $item_bid_amount[$itemKey]; // 订单采购总金额
            $trade_record[$itemKey]['order_new_amount'] = $trade_record_amount[$itemKey]; // 拆分后订单的金额
            
            $trade_record[$itemKey]['order_status'] = TradeRecordModel::$_status_waiting; // 订单交易状态 1-待付款 2-已付款 3-拒付
            $trade_record[$itemKey]['check_status'] = TradeRecordModel::$_check_status_n; // 实提登记状态 1-未登记 2-已登记
            $trade_record[$itemKey]['send_status'] = TradeRecordModel::$_send_status_n; // 发货状态 1-未发货 2-已发货
        }
        
//         Log::error('----------------------------------trade_record------------------------------params==>>' . var_export($trade_record, true));
//         EC::success(EC_OK);
        
        /**
         * 验证 授权码 
         */
        /* $code_model = $this->model('authorizationCode');
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
        } */
    
        /**
         * 验证 重复记录
         */
        $order_no = $order['order_num']; // 订单号
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
        if(empty($trade_record)){
            Log::error('create params is empty!');
            EC::fail(EC_PAR_BAD);
        }
        
        $data = $tradeRecord_model->create($trade_record);
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

        // for test
//         if($buyer_bank_info['data']['MBR_STS'] == '2'){//此处应该是1
//             Log::error('buyer bank not sign status:'.$buyer_bank_info['data']['MBR_STS']);
//             EC::fail(EC_NOT_SIGN);
//         }else if($buyer_bank_info['data']['MBR_STS'] == '3'){
//             Log::error('buyer bank already cancel status:'.$buyer_bank_info['data']['MBR_STS']);
//             EC::fail(EC_ARY_CANCEL);
//         }else if($buyer_bank_info['data']['ACCT_BAL'] < $data_obj['order_bid_amount']){
//             Log::error('buyer bank balance less');
//             EC::fail(EC_BLE_LESS);
//         }

        // 收款方用户ID
        $s_user_id = $data_obj['seller_id'];
        if(empty($s_user_id)){
            // 查询卖家支付账户的用户ID
            $data = $user_model->getInfo(array('company_name' => $data_obj['seller_name']) );
            if(EC_OK != $data['code']){
                Log::error("getInfo failed . company_name=" . $data_obj['seller_name'] );
                EC::fail($data['code']);
            } else {
                $data_info = $data['data'][0];
                if(empty($data_info)){
                     Log::error("getUserInfo empty . company_name=" . $data_obj['seller_name'] );
                     EC::fail(EC_USR_NON);
                } else {
                    $s_user_id = $data_info['id']; // 卖家/供应商ID（支付账户）
                }
            }
        }
        
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
        $order_sum_amount = $data_obj['order_bid_amount'];
        
        // 订单编号 （最长32位）
        $ctrt_no = 'D' . date('md',time()) . 'T' . date('His',time()) . 'N' . $order_no;
        // 商户交易流水号
        $mch_trans_no = 'D' . date('Ymd',time()) . 'T' . date('His',time()) . 'R' . rand(100,999) . 'U' . $user_id;
        
        $params_trade = array();
        $params_trade['MCH_NO'] = $mch_no; // 商户编号
        $params_trade['CTRT_NO'] = $ctrt_no; // 订单编号
        $params_trade['BUYER_SIT_NO'] = $buyer_sit_no; // 付款方席位号
        $params_trade['SELLER_SIT_NO'] = $seller_sit_no; // 收款方席位号
        $params_trade['FUNC_CODE'] = BcsTradeModel::$_FUNC_CODE_FINISH; // 功能号
        $params_trade['TX_AMT'] = $order_sum_amount; // 交易金额
        //$params_trade['TX_AMT'] = 2; // 交易金额 // TODO for test
        $params_trade['SVC_AMT'] = BcsTradeModel::$_SVC_AMT_0; // 买方佣金金额
        $params_trade['BVC_AMT'] = BcsTradeModel::$_BVC_AMT_0; // 卖方佣金金额
        $params_trade['CURR_COD'] = BcsTradeModel::$_CURR_COD_RMB; // 币别
        $params_trade['MCH_TRANS_NO'] = $mch_trans_no; // 商户交易流水号
        $params_trade['ORGNO'] = '0'; // 银票机构编号
        $params_trade['TICKET_NUM'] = BcsTradeModel::$_TICKET_NUM_0; // 使用票据数
        
        $params  = array();
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
        $params['id'] = $bcs_trade_id;
        
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
        $params['pay_timestamp'] = date('Y-m-d H:i:s',time());
        Log::notice('tradeRecord-update . params==>>' . var_export($params, true));
        $data = $tradeRecord_model->pay($params);
        if(EC_OK != $data['code']){
            Log::error('update-TradeRecord Fail! code=' . $data['code'] . ',msg=' . $data['msg'] ); // 仅仅记录日志，因为 实际交易已经成功。
        }
        
        EC::success(EC_OK);
    }
    
    private function createApply($is_advance = '0'){
        $data_info = array();
        
        $bcsCustomer_model = $this->model('bcsCustomer');
        $tradeRecord_model = $this->model('tradeRecord');
        $user_model = $this->model('user');
        
        $data = $tradeRecord_model->getNextId(array());
//         Log::notice("response-data ========22===================>> data = ##" . json_encode($data) . "##" );
        if(EC_OK != $data['code']){
            Log::error('getNextId Fail!');
            EC::fail($data['code']);
        }
        
        $data_info['id'] = $data['data'];
        $data_info['today'] = date('Y-m-d',time());
        
        $loginUser_data = UserController::getLoginUser();
//         Log::notice("response-data ========33===================>> loginUser_data = ##" . json_encode($loginUser_data) . "##" );
        $usercode = $loginUser_data['usercode'];
        $erp_fgsdm = $loginUser_data['erp_fgsdm'];
        
        $data = $user_model->erp_getInfo(array('usercode' => $usercode, 'fgsdm' => $erp_fgsdm) );
        Log::notice("response-data ========77===================>> loginUser_data = ##" . json_encode($data) . "##" );
        if(EC_OK_ERP != $data['code']){
            Log::error('erp_getInfo Fail!');
            EC::fail($data['code']);
        }
        $loginUser_data = $data['data'];
        
        $data_info['erp_fgsdm'] = $loginUser_data['erp_fgsdm'];
        $data_info['erp_bmdm'] = $loginUser_data['erp_bmdm'];
        $data_info['erp_fgsmc'] = $loginUser_data['erp_fgsmc'];
        $data_info['erp_bmmc'] = $loginUser_data['erp_bmmc'];
        $data_info['erp_username'] = $loginUser_data['username'];
        
        
        $user_info_data = $bcsCustomer_model->getInfo(array('user_id' => $usercode));
        if(EC_OK != $user_info_data['code']){
            Log::error("getInfo failed . ");
        }
        $user_info_data = $user_info_data['data'][0];
        if( !empty($user_info_data) ){
            $data_info['ACCOUNT_NO'] = $user_info_data['ACCOUNT_NO'];
            $data_info['record_bank_type'] = $user_info_data['record_bank_type'];
        }
        
        //对api接口调用数据返回处理
        if(ApiController::isApi()){
        	$api_data = array();
        	$api_data = $data_info;
        	EC::success(EC_OK, $api_data);
        }
        
        $view_html = $this->render('tradeRecordCreate', array('data_info' => $data_info, 'is_advance' => $is_advance), true);
        $this->render('index', array('page_type' => 'tradeRecord', 'tradeRecordCreate_html' => $view_html) );
    }
    
    public function create_add(){
    	
    	//$is_advance = strval(Request::post('is_advance')); //是否是预付款申请单  '1'是
    	$pay_pwd = Request::post('pay_pwd'); // 支付密码        
        $apply_no = Request::post('apply_no'); // 申请单号
        $comp_account = Request::post('comp_account'); // 收款账号
        $bank_name = Request::post('bank_name'); // 开户行
        $apply_total_amount = Request::post('apply_total_amount'); //申请总金额
        $amount_type = Request::post('amount_type'); // 款项类别
        $useTodo = Request::post('use'); // 用途
        $comment = Request::post('comment'); // 备注   
        $bank_no = Request::post('bank_no'); // 支付号
        $bank_flag = Request::post('bank_flag'); //本行/它行标志
        $local_flag = '1'; // Request::post('local_flag'); //同城异地标志 // 0-同城 1-异地        
        $erp_fgsdm = Request::post('erp_fgsdm'); // erp_分公司代码
        $erp_bmdm = Request::post('erp_bmdm'); // erp_部门代码
        $erp_fgsmc = Request::post('erp_fgsmc'); // erp_分公司名称
        $erp_bmmc = Request::post('erp_bmmc'); // erp_部门名称
        $erp_username = Request::post('erp_username'); // erp_用户名
        
        $apply_item = Request::post('order_no_arr'); // 业务单列表 @;
        $seller_name = Request::post('comp_name'); // 收款单位
        $seller_name_code = Request::post('comp_name_code'); // 收款单位代码
        
        $loginUser_data = UserController::getLoginUser();
        $user_id = $loginUser_data['usercode']; //当前登录用户id
        $audit_user_id_first = $loginUser_data['fuserid']; //一级审核人id
        $audit_user_id_second = $loginUser_data['managerid']; //二级审核人id
        
        //支付密码校验
        if(empty($pay_pwd)){
        	Log::error('create_add params error!');
        	EC::fail(6000, EC::$_errMsg[EC_PAR_ERR]);
        }
        $pay_pwd_data = array();
        $pay_pwd_data['usercode'] = $user_id;
        $pay_pwd_data['paypass'] = $pay_pwd;
        $user_model = $this->model('user');
        $res_data = $user_model->erp_payPwdVerify($pay_pwd_data);
        if(EC_OK_ERP != $res_data['code']){
        	Log::error('erp_payPwdVerify Fail!'. $res_data['msg']);
        	EC::fail(6000, $res_data['msg']);
        }
       
        //验证银行行号         
        $spdInteBank_model = $this->model('spdInternetBank');
        $bank_info_data = $spdInteBank_model->getInfo($params = array('bankNo'=>$bank_no));
        if(EC_OK != $bank_info_data['code']){
            Log::error("getInfo-spdInternetBank failed . ");
            EC::fail($bank_info_data['code']);
        }
        if( empty($bank_info_data['data'][0]) ){
            Log::error("getInfo-spdInternetBank empty . bank_info_data=" . json_encode($bank_info_data));
            EC::fail(EC_PAR_ERR);
        } 
        
        //查当前用户的虚拟帐号
        $bcsCustomer_model = $this->model('bcsCustomer');
        $user_info_data = $bcsCustomer_model->getInfo(array('user_id' => $user_id));
        if(EC_OK != $user_info_data['code']){
            Log::error("getInfo failed . ");
            EC::fail($user_info_data['code'], $user_info_data['msg']);
        }
        $ACCOUNT_NO = $user_info_data['data'][0]['ACCOUNT_NO'];
        
        $tradeRecord_model = $this->model('tradeRecord');
        $trade_record = array();
        $trade_record_item = array();        
        $sum_amount = 0; //非预付款的所有订单的申请金额的总计
        $order_no_str = ''; //非预付款的所有订单的单号
        $order_type = 0; //申请单类型 1预付款单
        
        $is_advance = '0';
        if(empty($apply_item)){
        	$is_advance = '1'; //如果订单列表为空则表示为预付款
        }
        
        //非预付款单
        if($is_advance != '1'){
	        foreach ($apply_item as $itemKey => $itemVal){
	            $arr = explode("@;",$itemVal);
	            $v_order_no = $arr[0]; //单个订单单号
	            $v_quote_amount = floatval($arr[1]); //单个订单的原始采购金额            
	            $v_comp_name_buyer = $arr[2]; //下游买家名称
	            $v_comp_name_buyer_code = $arr[3]; //下游买家代码
	            $v_amount = $arr[4]; //单个订单的申请金额
	            $order_no_str = $order_no_str . ',' . $v_order_no;
	            $sum_amount = $sum_amount + $v_amount;
	            $trade_record_item[$v_order_no]['order_no'] = $apply_no;
	            $trade_record_item[$v_order_no]['itme_no'] = $v_order_no;
	            $trade_record_item[$v_order_no]['bid_amount'] = $v_amount;
	            $trade_record_item[$v_order_no]['record_type'] = 2;
	            $trade_record_item[$v_order_no]['item_comp_name_buyer'] = $v_comp_name_buyer;
	            $trade_record_item[$v_order_no]['item_comp_name_buyer_code'] = $v_comp_name_buyer_code;
	            $trade_record_item[$v_order_no]['comment'] = $comment;
	            
	            //检查 采购单 金额 和 下游买家             
	            $data = $tradeRecord_model->erp_getSellOrderInfo(array('fphm'=>$v_order_no));
	            if(EC_OK_ERP != $data['code']){
	                Log::error('erp_getSellOrderInfo Fail! order_no=' . $v_order_no);
	                EC::fail($data['code']);
	            }
	            if( empty($data['data']) ){
	                Log::error('erp_getSellOrderInfo empty! order_no=' . $v_order_no);
	                EC::fail(EC_PAR_ERR);
	            }	            
	            $t_order_amount = floatval($data['data']['Header']['js_cgje']); // 金额
	            $t_order_buyer_code = $data['data']['Details'][0]['string8_']; // 下游买家代码	            
	            if( $v_amount > $t_order_amount ){
	                Log::error('check SellOrderInfo-order_amount - order_no=' . $v_order_no . ',amount=' . $t_order_amount . ' != v_amount=' . $v_amount);
	                EC::fail(EC_PAR_ERR);
	            }
	            if( $v_comp_name_buyer_code != $t_order_buyer_code ){
	                Log::error('check SellOrderInfo-order_buyer_code - order_no=' . $v_order_no . ',order_buyer_code=' . $t_order_buyer_code . ' != comp_name_buyer_code=' . $v_comp_name_buyer_code);
	                EC::fail(EC_PAR_ERR);
	            }
	        }        
        }else if($is_advance == '1'){
        	$order_type = 1; //预付款单
        }
        
        $trade_record['item'] = $trade_record_item;
        //$trade_record['order_type'] = $order_type; //申请单类型  1预付款单
        $trade_record['ACCOUNT_NO'] = $ACCOUNT_NO;
        $trade_record['user_id'] = $user_id;
        $trade_record['audit_user_id_first'] = $audit_user_id_first;
        $trade_record['audit_user_id_second'] = $audit_user_id_second;
        $trade_record['order_no'] = substr($order_no_str,1);
        $trade_record['order_bid_amount'] = $sum_amount;        
        $trade_record['apply_no'] = $apply_no;
        $trade_record['seller_name'] = $seller_name;
        $trade_record['seller_name_code'] = $seller_name_code;
        $trade_record['comp_account'] = $comp_account;
        $trade_record['bank_name'] = $bank_name;
        $trade_record['amount_type'] = $amount_type;
        $trade_record['useTodo'] = $useTodo;
        $trade_record['comment'] = $comment;
        $trade_record['record_type'] = 2;
        $trade_record['order_timestamp'] = date('Y-m-d',time());         
        $trade_record['order_status'] = 1; //订单交易状态 1-待付款 2-已付款 
        $trade_record['bank_no'] = $bank_no;
        $trade_record['bank_flag'] = $bank_flag;
        $trade_record['local_flag'] = $local_flag;        
        $trade_record['erp_fgsdm'] = $erp_fgsdm;
        $trade_record['erp_bmdm'] = $erp_bmdm;
        $trade_record['erp_fgsmc'] = $erp_fgsmc;
        $trade_record['erp_bmmc'] = $erp_bmmc;
        $trade_record['erp_username'] = $erp_username;        
        
        Log::notice("create_add request =============>> data = ##" . json_encode($trade_record) . "##" ); 
        $data = $tradeRecord_model->create_add($trade_record);
        if(EC_OK != $data['code']){
            Log::error('create Fail!');
            EC::fail($data['code'], $data['msg']);
        }
        EC::success(EC_OK);
    }
    
    public function checkBankName(){
        $bankName = Request::post('bankName');
//         Log::notice(var_export($_POST,true));
        
        if( !$bankName ){
            Log::error('check params is empty !' . $bankName);
            EC::fail(EC_PAR_ERR);
        }
        
//         $spdBank_model = $this->model('spdBank');
//         $data = $spdBank_model->queryBankNumberByName(array('bankName'=>$bankName));
//         Log::notice("response-data ===========checkBankName================>> data = ##" . json_encode($data) . "##" );
//         $data = $data['body']['lists']['list'];

        $spdInternetBank_model = $this->model('spdInternetBank');
        $params = array();
        $params['bankName'] = $bankName;
        $data = $spdInternetBank_model->getInfo($params);
        Log::notice("response-data ===========checkBankName================>> data = ##" . json_encode($data) . "##" );
        
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
        $data = $data['data'][0];
        
        EC::success(EC_OK, $data);
    }
    
    public function erp_getOrderBuy(){
        $current_page = Request::post('page');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $fphm = Request::post('fphm');
        $dwmc = Request::post('dwmc');
        $comp_name = Request::post('comp_name');
        
//         Log::notice("response-data ===========================>> erp_getOrderBuy  dwmc=" . $dwmc);
        $tradeRecord_model = $this->model('tradeRecord');
        
        if(!$current_page || 0 >= $current_page) {
            $current_page = 1;
        }
        
        $BeginDate = date('Y-m-01', strtotime(date("Y-m-d"))); // 当月第一天
        if(!$time1){
            $time1 = $BeginDate;
        }
        if(!$time2){
            $time2 = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
        }
        
        $conf = $this->getConfig('conf');
        $page_cnt = $conf['page_count_default'];
        
        $loginUser = UserController::getLoginUser();
        
        $params = array();
        $params['page'] = $current_page;
        $params['rows'] = $page_cnt;
        $params['ksrq'] = $time1;
        $params['jzrq'] = $time2;
        $params['fphm'] = $fphm;
        $params['fgs'] = $loginUser['erp_fgsdm'];
        
        if($comp_name){
            $params['dwmc'] = $comp_name;
            $dwmc = $comp_name;
        }else if($dwmc){
            $params['dwmc'] = $dwmc . '%';
        }
        
        $data = $tradeRecord_model->erp_getOrderBuyList($params);
        if(EC_OK_ERP != $data['code']){
            Log::error('erp_getOrderBuyList Fail!');
            EC::fail($data['code']);
        }
        Log::notice("response-data ===========================>> data = ##" . json_encode($data) . "##" );
        
        $data_list = $data['data']['data'];
        $cnt = $data['data']['records'];
        
        $total_page = ($cnt % $page_cnt) ? (integer)($cnt / $page_cnt) + 1 : $cnt / $page_cnt;
        Log::notice($page_cnt . " -response-data ======================total_page=====>> data = ##" . $total_page. "##cnt="  . $cnt);
        
        if(!$current_page || 0 >= $current_page) {
            $current_page = 1;
        } if($current_page > $total_page) {
            $current_page = $total_page;
        }
        
        $params['dwmc'] = $dwmc;
        if($comp_name){
            $params['lock'] = true;
        }
        
        $entity_list_html = $this->render('erpOrderBuy_list', array( 'params' => $params, 'data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
    }
    
    public function erp_getOrderBuyInfo($fphm = NULL){       
        $fphm = ($fphm == NULL) ? Request::post('fphm') : $fphm;
        
        if(!$fphm){
            Log::error('checkCode params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        $tradeRecord_model = $this->model('tradeRecord');
        
        $params = array();
        $params['fphm'] = $fphm;
        
        $data = $tradeRecord_model->erp_getOrderBuyInfo($params);
        if(EC_OK_ERP != $data['code']){
            Log::error('erp_getOrderBuyInfo Fail!');
            EC::fail($data['code']);
        }
        
        //Log::write(var_export($data, true), 'debug', 'debug1-'.date('Y-m-d'));
        Log::notice("response-data ===========OrderBuyInfo================>> data = ##" . json_encode($data) . "##" );
        
        EC::success(EC_OK, $data['data']);
    }
    
    public function erp_getSellOrderList(){
    	$current_page = Request::post('page');
    	$time1 = Request::post('time1');
    	$time2 = Request::post('time2');
    	$fphm = Request::post('fphm');
    	$status = Request::post('status'); 
    	$comp_name_code = Request::post('dwdm');
    	$comp_name = Request::post('dwmc');
    
    	//         Log::notice("response-data ===========================>> erp_getOrderBuy  dwmc=" . $dwmc);
    	$tradeRecord_model = $this->model('tradeRecord');
    
    	if(!$current_page || 0 >= $current_page) {
    		$current_page = 1;
    	}
    
    	$BeginDate = date('Y-m-01', strtotime(date("Y-m-d"))); // 当月第一天
    	if(!$time1){
    		$time1 = $BeginDate;
    	}
    	if(!$time2){
    		$time2 = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
    	}
    
    	$conf = $this->getConfig('conf');
    	$page_cnt = $conf['page_count_default'];
    
    	$loginUser = UserController::getLoginUser();
    
    	$params = array();
    	$params['fphm'] = $fphm;
    	$params['ksrq'] = $time1;
    	$params['jzrq'] = $time2;    	
    	//$params['fgs'] = $loginUser['erp_fgsdm'];
    	$params['djzt'] = $status;
    	if($comp_name_code){
    		$params['dwdm_3'] = $comp_name_code;
    		$dwmc = $comp_name;
    	}else if($comp_name){
    		$params['dwmc_3'] = $comp_name . '%';
    	}
    	$params['page'] = $current_page;
    	$params['rows'] = $page_cnt;
    	
    
    	$data = $tradeRecord_model->erp_getSellOrderList($params);
    	if(EC_OK_ERP != $data['code']){
    		Log::error('erp_getSellOrderList Fail!');
    		EC::fail($data['code']);
    	}
    	Log::notice("response-data ===========================>> data = ##" . json_encode($data) . "##" );
    
    	$data_list = $data['data']['data'];
    	$cnt = $data['data']['records'];
    
    	$total_page = ($cnt % $page_cnt) ? (integer)($cnt / $page_cnt) + 1 : $cnt / $page_cnt;
    	Log::notice($page_cnt . " -response-data ======================total_page=====>> data = ##" . $total_page. "##cnt="  . $cnt);
    
    	if(!$current_page || 0 >= $current_page) {
    		$current_page = 1;
    	} if($current_page > $total_page) {
    		$current_page = $total_page;
    	}
    
    	$params['dwdm'] = $comp_name_code;
    	$params['dwmc'] = $comp_name;
    	if($comp_name){
    		$params['lock'] = true;
    	}
    
    	$entity_list_html = $this->render('erpSellOrder_list', array( 'params' => $params, 'data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
    	EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
    }
    
    public function erp_getSellOrderInfo($fphm = NULL){
    	$fphm = ($fphm == NULL) ? Request::post('fphm') : $fphm;
    
    	if(!$fphm){
    		Log::error('checkCode params error!');
    		EC::fail(EC_PAR_ERR);
    	}
    
    	$tradeRecord_model = $this->model('tradeRecord');
    
    	$params = array();
    	$params['fphm'] = $fphm;
    
    	$data = $tradeRecord_model->erp_getSellOrderInfo($params);
    	if(EC_OK_ERP != $data['code']){
    		Log::error('erp_getSellOrderInfo Fail!');
    		EC::fail($data['code']);
    	}
    
    	//Log::write(var_export($data, true), 'debug', 'debug1-'.date('Y-m-d'));
    	Log::notice("response-data ===========erp_getSellOrderInfo================>> data = ##" . json_encode($data) . "##" );
    
    	EC::success(EC_OK, $data['data']);
    }
     
    //付款单同步erp $id 
    public function erp_syncBillsOfPayment($id = NULL, $is_ec = 0){
    	
    	$id = ($id == NULL) ? intval(Request::post('id')) : intval($id);
    	$is_ec = ($is_ec == 0) ? intval(Request::post('is_ec')) : intval($is_ec);
    	Log::fkdNotice("erp_syncBillsOfPayment ===>> id=" .$id ." is_ec=".$is_ec);
    	
    	if(empty($id)){
    		Log::fkdError('id empty !');
    		if($is_ec) EC::fail(EC_PAR_ERR);
    		return false;
    	}
    	 
    	//根据id查采购单的数据
    	$tradeRecord_model = $this->model('tradeRecord');
    	$data = $tradeRecord_model->getInfo(array('id' => $id));
    	if(empty($data) || !is_array($data) || EC_OK != $data['code'] || !isset($data['data'])) {
    		Log::fkdError('tradeRecord getInfo empty !');
    		if($is_ec) EC::fail(EC_DAT_NON);
    		return false;
    	}
    	$data = $data['data'][0];
    	if(empty($data)) {
    		Log::fkdError('tradeRecord getInfo empty !');
    		if($is_ec) EC::fail(EC_RED_EMP);
    		return false;
    	}
    	//Log::write(var_export($data, true), 'debug', 'debug2-'.date('Y-m-d'));
    	 
    	//判断是否已审批通过
    	if(5 != intval($data['apply_status'])){
    		Log::fkdError('audit did not pass!');
    		if($is_ec) EC::fail(EC_TRADE_TF_NO_AS);
    		return false;
    	}
    	
    	//判断是否已付款  order_status 订单交易状态 1-待付款 2-已付款' || 记录状态 0-待补录；1-待记帐；2-待复核；3-待授权；4-完成；8-拒绝；9-撤销；
    	if(2 != intval($data['order_status'])){
    		Log::fkdError("the order has not been payment: order_status={$data['order_status']}!");
    		if($is_ec) EC::fail(EC_TRADE_TF_OS_ERR_2);
    		return false;
    	}
    	if(true && !in_array($data['backhost_status'], array(0,1,2,3,4))){
    		Log::fkdError("the order has not been payment: backhost_status={$data['backhost_status']}!");
    		if($is_ec) EC::fail(EC_TRADE_TF_OS_ERR_3);
    		return false;
    	}
    	
    	//判断是否已同步erp
    	if(2 == $data['is_erp_sync']){
    		Log::fkdError("the order has been sync erp: is_erp_sync={$data['is_erp_sync']}!");
    		if($is_ec) EC::fail(EC_REC_EST);
    		return false;
    	}
    	    	
    	//查合伙人信息
    	$bcs_params  = array();
    	$bcs_params['user_id'] = $data['user_id'];
    	//Log::write("user_id==".$data['user_id'], 'debug', 'debug-'.date('Y-m-d'));
    	$bcsCustomer_model = $this->model('bcsCustomer');
    	$bcs_data = $bcsCustomer_model->getInfo($bcs_params);
    	//Log::write("bcs_data==".var_export($bcs_data, true), 'debug', 'debug-'.date('Y-m-d'));
    	if(EC_OK != $bcs_data['code'] || !is_array($bcs_data) || !isset($bcs_data['data'])){
    		Log::fkdError("bcsCustomer getInfo failed . ");
    		if($is_ec) EC::fail(EC_USR_NON);
    		return false;
    	}
    	$bcs_data = $bcs_data['data'][0];
    	if(empty($bcs_data)) {
    		Log::fkdError('bcsCustomer getInfo empty !');
    		if($is_ec) EC::fail(EC_RED_EMP);
    		return false;
    	}
    	/*
    	//根据供货商单位名查erp接口来往单位信息
    	$ct_params = array();
    	$ct_params['dwmc'] = '湖南金荣钢贸有限公司';//$data['seller_name'];
    	$user_model = $this->model('user');
    	$ct_info_data = $user_model->erp_getContactCompanyInfo($ct_params);
    	if(EC_OK_ERP != $ct_info_data['code']){
    		Log::error('erp_getContactCompanyInfo Fail!');
    		EC::fail($ct_info_data['code']);
    	}
    	$ct_info_data = $ct_info_data['data']['data'];
    	$dwdm = '';
    	if(!empty($ct_info_data) && isset($ct_info_data[0])){
    		$dwdm = $ct_info_data[0]['dwdm'];
    	} */
    	
    	//获取详细订单数据
    	$tradeRecordItem_model = $this->model('tradeRecordItem');    	
    	$data_list = $tradeRecordItem_model->searchList(array('trade_record_id' => $data['id']));
    	if($data_list['code'] !== EC_OK){
    		Log::fkdError('tradeRecordItem searchList error');
    	}
    	$data['list'] = $data_list['data'] ? $data_list['data'] : [];
    	
    	/* "head":{
    	"dwdm":"00002247",
    	"rq":"2016-5-1",
    	"je":93806.00,
    	"usercode":"110005",
    	"gszh":"1901007009200157256",
    	"gskhh":"中国工商银行",
    	"zh":"7402710182600006306",
    	"khh":"中信银行长沙伍家岭支行"
    	},
    	"details":[{
    	"xh":"1",
    	"je":"1000",
    	"ywdjh":"TT12345678",
    	}] */

    	//组织提交参数
    	$params = array();
    	$head = array();
    	$head['dwdm'] = $data['seller_name_code']; //往来单位代码：dwdm_
    	$head['rq'] = $data['pay_timestamp']; //日期：rq_
    	$head['je'] = $data['order_bid_amount']; //总金额：je_
    	$head['usercode'] = $data['user_id']; 
    	$head['gszh'] = $data['ACCOUNT_NO']; //付款公司帐户：gszh_（浦发银行帐号）
    	$head['gskhh'] = '上海浦东发展银行'; //付款开户银行：gskhh_（浦发银行）
    	$head['zh'] = $data['comp_account']; //收款银行帐号
    	$head['khh'] = $data['bank_name']; //收款银行名称
    	$params['head'] = $head;
    	
    	$params['details'] = array();
    	foreach ($data['list'] as $item){
    		$details = array();
    		$details['xh'] = $item['id']; //序号
    		$details['je'] = $item['bid_amount']; //单据金额
    		$details['ywdjh'] = $item['itme_no']; //单据号
    		$params['details'][] = $details;
    	}
    	
    	Log::fkdNotice("request-data ============>> data = ##" . json_encode($params) . "##");    	
    	$is_erp_sync = 2;    	
    	$tradeRecord_model = $this->model('tradeRecord');
    	$res_data = $tradeRecord_model->erp_syncBillsOfPayment($params);
    	Log::fkdNotice("response-data ============>> data = ##" . json_encode($res_data) . "##");
    	 
    	if(EC_OK_ERP != $res_data['code']){
    		Log::fkdError('erp_syncBillsOfPayment Fail!'. $res_data['msg']);
    		//EC::fail($res_data['code']);
    		$is_erp_sync = 3;
    	}    	
    	
    	/**/
    	//修改同步状态
    	$up_params = array(); 
    	$up_params['id'] = $data['id'];   	
    	$up_params['is_erp_sync'] = $is_erp_sync; //付款单是否同步 1否 2同步 3同步失败
    	$up_params['erp_sync_timestamp'] = date('Y-m-d H:i:s',time()); 
    	$tr_data = $tradeRecord_model->update($up_params);
    	if(EC_OK != $tr_data['code']){
    		Log::fkdError('update order status fail!');
    		if($is_ec) EC::fail($tr_data['code']);
    		return false;
    	}
    	
    	if(3 == $is_erp_sync){
    		if($is_ec) EC::fail($res_data['code'], $res_data['msg']);
    		return false;
    	}
    	
    	if($is_ec) EC::success(EC_OK);
    	return true;
    }

    
    /**
    * 付款申请单提交erp接口是否可以审批通过
    * @date: 2016-3-30 下午2:00:12
    * @author: lw
    * @param: $tradeRecord_data 可为id值或数组
    * @return:
    */
    public function erp_auditOneTradRecord($tradeRecord_data){
    	
    	if(empty($tradeRecord_data) && !is_int($tradeRecord_data) && !is_array($tradeRecord_data)){
    		Log::auditError('tradeRecord_data params error!');
    		EC::fail(EC_PAR_ERR);
    	}
    	
    	$tradeRecord_model = $this->model('tradeRecord');
    	$id = 0;
    	//如果是id则查
    	if(is_int($tradeRecord_data)){
    		$id = $tradeRecord_data;
    		//根据id查采购单的数据    		
    		$tr_data = $tradeRecord_model->getInfo(array('id' => $id));
    		if(empty($tr_data) || !is_array($tr_data) || EC_OK != $tr_data['code'] || !isset($tr_data['data'])) {
    			Log::auditError('tradeRecord getInfo empty !');
    			EC::fail(EC_DAT_NON);
    		}
    		$tradeRecord_data = $tr_data['data'][0];
    		if(empty($tradeRecord_data)) {
    			Log::auditError('tradeRecord getInfo empty !');
    			EC::fail(EC_RED_EMP);
    		}
    	}elseif(is_array($tradeRecord_data)){
    		if(!isset($tradeRecord_data['id']) || !isset($tradeRecord_data['user_id'])){
    			Log::auditError('id or user_id is empty !');
    			EC::fail(EC_PAR_ERR);
    		}
    		$id = $tradeRecord_data['id'];
    	}
    	
    	//查所有item
    	$tradeRecordItem_model = $this->model('tradeRecordItem');
    	$item_list = $tradeRecordItem_model->searchList(array('trade_record_id' => $id));
    	if($item_list['code'] !== EC_OK){
    		Log::auditError('tradeRecordItem searchList error');
    		EC::fail(EC_DAT_NON);
    	}
    	$item_list = $item_list['data'] ? $item_list['data'] : [];
    	
    	//组织提交参数
    	$params = array();
    	$head = array();
    	$head['usercode'] = $tradeRecord_data['user_id'];
    	//$params['head']['order_bid_amount'] = $tradeRecord_data['order_bid_amount'];
    	$params['head'] = $head;
    	$params['details'] = array();    	
    	foreach ($item_list as $item){
    		$details = array();
    		$details['xymjdm'] = $item['item_comp_name_buyer_code'];
    		$details['fkje'] = $item['bid_amount'];
    		$params['details'][] = $details;
    	}
    	
    	 	
    	Log::auditNotice("request-params  ===>> params = ##" . json_encode($params) . "##");
    	$res_data = $tradeRecord_model->erp_auditOneTradRecord($params);
    	Log::auditNotice("response-params ===>> res_data = ##" . json_encode($res_data) . "##");
    	
    	if(EC_OK_ERP != $res_data['code']){
    		Log::auditError('erp_auditOneTradRecord Fail!'.$res_data['msg']);
    		if(!empty($res_data['data']) && isset($res_data['data']['ReturnCode']) ){
    			EC::fail($res_data['data']['ReturnCode'], $res_data['data']['ReturnMsg']);
    		}    		
    		EC::fail($res_data['code'], "erp审核错误：".$res_data['msg']);
    	}  
    	
    	return true;
    }

    //审批付款申请单
    protected function auditOneTradRecord(){
    	$id = intval(Request::post('id'));
    	$vcode = Request::post('vcode');
    	$apply_status = intval(Request::post('apply_status'));   
    	
    	Log::auditNotice("request-data ===>> id=" .$id . "apply_status=" . $apply_status . "##" );
    	
    	if(empty($id) || empty($apply_status)){
    		Log::auditError('id or apply_status params error!');
    		EC::fail(EC_PAR_ERR);
    	}
    	
    	//根据id查采购单的数据
    	$tradeRecord_model = $this->model('tradeRecord');
    	$data = $tradeRecord_model->getInfo(array('id' => $id));
    	if(empty($data) || !is_array($data) || EC_OK != $data['code'] || !isset($data['data'])) {
    		Log::auditError('tradeRecord getInfo empty !');
    		EC::fail(EC_DAT_NON);
    	}
    	$data = $data['data'][0];
    	if(empty($data)) {
    		Log::auditError('tradeRecord getInfo empty !');
    		EC::fail(EC_RED_EMP);
    	}
    	//Log::write(var_export($data, true), 'debug', 'debug-'.date('Y-m-d'));
    	//申请状态 1一级待审核 2一级审核通过 3一级审核驳回 4二级待审核 5二级审核通过 6二级审核驳回
    	//audit_user_id_first audit_user_id_second
    	
    	$current_user_id = self::getCurrentUserId();
    	$audit_level =0;
    	    	
    	if(2 == $apply_status || 3 == $apply_status){ //一级审批   	
    		
    		if(2 == $apply_status ){
    			//查审核人手机号码
    			$mobile = UserController::getUserMobileByUserId($current_user_id);
    			
    			//检查验证码是否相等    			
    			SmsController::checkSmsVerificationCode($mobile, 11, $vcode);
    		}    		
    		
    		//判断当前用户是否有审核权限    		
    		if($current_user_id != $data['audit_user_id_first']){
    			Log::auditError('1 the current user does not have audit authority!'. 'id='. $id .' ' . $current_user_id . '!=' . $data['audit_user_id_second']);
    			EC::fail(EC_USER_NO_AUTH);
    		}
    		//判断是否可以审批
    		if(1 != intval($data['apply_status'])){
    			Log::auditError('1 audit did not pass!');
    			EC::fail(EC_TRADE_TF_YES_AS);
    		}
    		$audit_level = 1;
    	}elseif(5 == $apply_status || 6 == $apply_status){ //二级审批
    		//判断当前用户是否有审核权限
    		if($current_user_id != $data['audit_user_id_second']){
    			Log::auditError('2 the current user does not have audit authority!'. 'id='. $id .' ' . $current_user_id . '!=' . $data['audit_user_id_second']);
    			EC::fail(EC_USER_NO_AUTH);
    		}
    		
    		//判断是否可以审批
    		if(2 != intval($data['apply_status']) && 4 != intval($data['apply_status'])){
    			Log::auditError('2 audit did not pass!');
    			EC::fail(EC_TRADE_TF_FRIST_NO_AS);
    		}
    		$audit_level = 2;
    	}else{
    		Log::auditError('apply_status is error!');
    		EC::fail(EC_PAR_ERR);
    	}
    	
    	//查合伙人信息
    	$params  = array();
    	$params['user_id'] = $data['user_id'];
    	//Log::write("user_id==".$data['user_id'], 'debug', 'debug-'.date('Y-m-d'));
    	$bcsCustomer_model = $this->model('bcsCustomer');
    	$bcs_data = $bcsCustomer_model->getInfo($params);
    	//Log::write("bcs_data==".var_export($bcs_data, true), 'debug', 'debug-'.date('Y-m-d'));
    	if(EC_OK != $bcs_data['code'] || !is_array($bcs_data) || !isset($bcs_data['data'])){
    		Log::auditError("bcsCustomer getInfo failed . ");
    		EC::fail(EC_USR_NON);
    	}
    	$bcs_data = $bcs_data['data'][0];
    	if(empty($bcs_data)) {
    		Log::auditError('bcsCustomer getInfo empty !');
    		EC::fail(EC_RED_EMP);
    	}
    	
    	//账户余额判断
    	if(floatval($data['order_bid_amount']) > floatval($bcs_data['ACCT_BAL'])){
    		Log::auditError('order_bid_amount'.$data['order_bid_amount']. '> ACCT_BAL!'.$bcs_data['ACCT_BAL']);
    		EC::fail(EC_BLE_LESS);
    	}
    	
    	//调用erp接口查询是否可以通过
    	//$erp_audit_result = $this->erp_auditOneTradRecord($data);
    	
    	//修改审批状态
    	$params = array();
    	$params['id'] = $id;
    	$params['apply_status'] = $apply_status; 
    	$params['audit_level'] = $audit_level;
    	$params['apply_timestamp'] = date('Y-m-d H:i:s',time());
     	$tradeRecord_model = $this->model('tradeRecord');
    	$data = $tradeRecord_model->auditOneTradRecord($params);    	
    	Log::auditNotice("response-data ===========================>> data = ##" . json_encode($data) . "##" );
    	    	
    	if(EC_OK != $data['code']){
    		Log::auditError('auditOneTradRecord Fail!');
    		EC::fail($data['code']);
    	}
    	
    	/* //对审批通过的进行付款
    	if(2 == $apply_status){
    		$this->sendTransferTrade($id);
    	} */
    	
    	EC::success(EC_OK, $data['data']);
    }

    public static function getBackhostStatus(){
    	$arr = array(
    		'0' => '待补录',
    		'1' => '待记帐',
    		'2' => '待复核',
    		'3' => '待授权',
    		'4' => '完成',
    		'8' => '拒绝',
    		'9' => '撤销'
    	); 
    	return $arr;
    }

    public static function getBackhostStatusByKey($key){
    	$arr = self::getBackhostStatus();
    	if(array_key_exists($key, $arr)){
    		return $arr[$key];
    	}
    	return null;    	
    }
    
    public static function getApplyStatus(){
    	//1一级待审核 2一级审核通过 3一级审核驳回 4二级待审核 5二级审核通过 6二级审核驳回
    	$arr = array(    			
    			'1' => '一级待审核',
    			'2' => '一级审核通过',
    			'3' => '一级审核驳回',
    			'4' => '二级待审核',
    			'5' => '二级审核通过',
    			'6' => '二级审核驳回'
    	);
    	return $arr;
    }
    
    public static function getApplyStatusByKey($key, $audit_level='0'){
    	$arr = self::getApplyStatus();
    	if('2' == strval($audit_level)){
    		if('2' == strval($key)){
    			$key = 4;
    		}
    	}
    	if(array_key_exists($key, $arr)){
    		return $arr[$key];
    	}
    	return null;
    }
   
    /**
    * 对已审核且待付款的采购单进行付款  
    */
    protected function sendTransferTrade($id = NULL){
    	    	
    	$id = ($id == NULL) ? intval(Request::post('id')) : intval($id);    	
    	Log::fkNotice("sendTransferTrade ===>> id=" .$id );    	
    	
    	if(empty($id)){
    		Log::fkError('the id empty !');
    		EC::fail(EC_PAR_ERR);
    	}
    	
    	//检查当前登录的用户是否是后台管理人员
    	if(!AdminController::isAdmin()){
    		Log::fkError('the current user is not admin, current_user_id='. UserController::getCurrentUserId());
    		EC::fail(EC_USER_NO_AUTH);
    	}
    	
    	//根据id查采购单的数据    	
    	$tradeRecord_model = $this->model('tradeRecord');    	
    	$data = $tradeRecord_model->getInfo(array('id' => $id));        	
    	if(empty($data) || !is_array($data) || EC_OK != $data['code'] || !isset($data['data'])) {
    		Log::fkError('tradeRecord getInfo empty !');
    		EC::fail(EC_DAT_NON);
    	}    	    	
    	$data = $data['data'][0];
    	if(empty($data)) {
    		Log::fkError('tradeRecord getInfo empty !');
    		EC::fail(EC_RED_EMP);
    	}    		
    	//Log::write(var_export($data, true), 'debug', 'debug-'.date('Y-m-d'));
    	
    	//判断二级是否已审批通过
    	if(5 != intval($data['apply_status'])){    		
    		Log::fkError('second audit did not pass!');
    		EC::fail(EC_TRADE_TF_SECOND_NO_AS);
    	}
    	    	
    	//判断是否已付款  
    	//order_status 订单交易状态 1-待付款 2-已付款  
    	if(2 == intval($data['order_status'])){
    		Log::fkError("the order has been payment: order_status={$data['order_status']}!");
    		EC::fail(EC_TRADE_TF_OS_ERR_2);
    	}
    	//backhost_status 记录状态 0-待补录；1-待记帐；2-待复核；3-待授权；4-完成；8-拒绝；9-撤销；
    	if($data['backhost_status']!=null && in_array($data['backhost_status'], array(0,1,2,3,4))){
    		Log::fkError("the order has been payment: backhost_status={$data['backhost_status']}!");
    		EC::fail(EC_TRADE_TF_OS_ERR_3);
    	}
    	
    	//判断当前用户是否有该笔申请单付款权限
    	$current_user_id = UserController::getCurrentUserId();
    	if($current_user_id != $data['audit_user_id_second']){
    		Log::fkError('the current user does not have operation permissions, current_user_id='. UserController::getCurrentUserId() . ' audit_user_id_second='. $data['audit_user_id_second']);
    		EC::fail(EC_USER_NO_AUTH);
    	}
    	  
    	//查合伙人信息    	   
    	$params  = array();
    	$params['user_id'] = $data['user_id'];  
    	//Log::write("user_id==".$data['user_id'], 'debug', 'debug-'.date('Y-m-d'));
    	$bcsCustomer_model = $this->model('bcsCustomer');  	
    	$bcs_data = $bcsCustomer_model->getInfo($params); 
    	//Log::write("bcs_data==".var_export($bcs_data, true), 'debug', 'debug-'.date('Y-m-d'));    	
    	if(EC_OK != $bcs_data['code'] || !is_array($bcs_data) || !isset($bcs_data['data'])){
    		Log::fkError("bcsCustomer getInfo failed . ");
    		EC::fail(EC_USR_NON);
    	}
    	$bcs_data = $bcs_data['data'][0];
    	if(empty($bcs_data)) {
    		Log::fkError('bcsCustomer getInfo empty !');
    		EC::fail(EC_RED_EMP);
    	}

    	//账户余额判断
    	if(floatval($data['order_bid_amount']) > floatval($bcs_data['ACCT_BAL'])){
    		Log::fkError('order_bid_amount'.$data['order_bid_amount']. '> ACCT_BAL!'.$bcs_data['ACCT_BAL']);
    		EC::fail(EC_BLE_LESS);
    	}
    	 	
    	$SIT_NO = $bcs_data['SIT_NO'];
    	$ACCOUNT_NO = $data['ACCOUNT_NO'];
    	
    	//附言超过42个字节则截取
    	$useTodo = $data['useTodo'];
    	if(!empty($useTodo) && ((strlen($useTodo) + mb_strlen($useTodo,'utf-8')) / 2) > 21){
    		$useTodo = mb_substr($useTodo, 0, 21, 'utf-8');
    	}
    	
    	//必要字段值检测是否为空
    	if(empty($ACCOUNT_NO) || empty($SIT_NO) || empty($data['comp_account']) || empty($data['seller_name']) || empty($data['order_bid_amount'])){
    		Log::fkError("Some data in an empty value. ");
    		EC::fail(EC_DATA_EMPTY_ERR);
    	}

    	//付款给银行    	
    	try { 
    		$params = array();
	    	$params['payerVirAcctNo']   = $ACCOUNT_NO; //Y 付款人虚账号
	    	$params['payerName']        = $SIT_NO; //Y 付款人名称    	
	    	$params['payeeAcctNo']  = $data['comp_account']; //Y 收款人账号
	    	//$params['payeeAcctNo']    = '6223635001004485218'; // 收款人账号
	    	$params['payeeAcctName'] = $data['seller_name']; //Y 收款人中文名 
	    	//$params['payeeAcctName']  = '钟煦镠'; // 收款人中文名
	    		
	    	$params['ownItBankFlag']    = $data['bank_flag'];//Y 本行/它行标志 0：表示本行 1：表示它行   
			$params['remitLocation']    = $data['local_flag']; // 同城异地标志 0：同城 1：异地 跨行转账时必须输入(即本行/它行标志为1：表示它行)
	    	$params['payeeBankName']    = $data['bank_name']; // 收款行名称 跨行转账时必须输入(即本行/它行标志为1：表示它行)
	    	$params['payeeBankAddress'] = $data['bank_name']; // 收款行地址 跨行转账时必须输入(即本行/它行标志为1：表示它行)   
	    	$params['payeeBankNo']      = $data['bank_no']; // 支付号 【收款账号行号】    	
	    	$params['transAmount']      = $data['order_bid_amount']; //Y 交易金额
	    	$params['note']             = $useTodo; // 附言 如果跨行转账，附言请不要超过42字节（汉字21个）
	    	   	
	   		//提交付款前记录日志
	    	Log::fkNotice("request-data ===sendTransferTrade===>> params = ##" . json_encode($params) . "##");
	    	
	    	$sp_data = array();    	
	    	$spdBank_model = $this->model('spdBank');	    	
	    	$sp_data = $spdBank_model->sendTransferTrade($params);
	    	$sp_data = $sp_data['body'];
	    	 /*   
            $sp_data['jnlSeqNo'] = '123456789123456789';
	    	$sp_data['backhostStatus'] = 4;
	    	*/
	    	$jnl_seq_no = $sp_data['jnlSeqNo']; //业务流水号
	   		$backhost_status = $sp_data['backhostStatus']; //付款后返回的记录状态 0-待补录；1-待记帐；2-待复核；3-待授权；4-完成；8-拒绝；9-撤销；
         
	    	//付款后记录日志
	    	Log::fkNotice("response-data ===sendTransferTrade===>> sp_data = ##" . json_encode($sp_data) . "##");
	    		   		
    	}catch (Exception $e){   			
   			Log::fkError('sendTransferTrade . e==========' . $e->getMessage());
   			EC::fail(EC_OPE_FAI);
    	}
   		
   		//付款成功后修改付款状态   		
   		$up_params = array();
   		$up_params['id'] = $id;
   		if($backhost_status <=4){
   			$up_params['order_status'] = 2; //订单交易状态 1-待付款 2-已付款 ',
   		}   		
   		$up_params['pay_timestamp'] = date('Y-m-d H:i:s',time());
   		$up_params['jnl_seq_no'] = $jnl_seq_no;
   		$up_params['backhost_status'] = $backhost_status;
   		$tradeRecord_model = $this->model('tradeRecord');   		
   		$tr_data = $tradeRecord_model->update($up_params);
   		if(EC_OK != $tr_data['code']){
   			Log::fkError('update order status fail!');
   			EC::fail($tr_data['code']);
   		}
   		$sp_data['backhostDesc'] = self::getBackhostStatusByKey($backhost_status);   		

   		if($backhost_status !=8 && $backhost_status!=9){   
   			
   			//同步付款单给erp
   			$this->erp_syncBillsOfPayment($id);
   			
   			//更新流水   			
   			$this->instance('BcsTradeController')->spd_loadAccountTradeList_exec($ACCOUNT_NO);
   			
   			//更新余额   	
   			$this->instance('BcsCustomerController')->spd_loadAccountList_exec($ACCOUNT_NO); 			   			
   			   			
   		}
   		
   		EC::success(EC_OK, $sp_data);
    }

    public function erp_getOrgNameInfo(){
        $dwmc = Request::post('dwmc'); // 单位名称
    
        if(!$dwmc){
            Log::error('checkCode params error!');
            EC::fail(EC_PAR_ERR);
        }
    
        $tradeRecord_model = $this->model('tradeRecord');
    
        $params = array();
        $params['dwmc'] = $dwmc;
    
        $data = $tradeRecord_model->erp_getOrgNameInfo($params);
        if(EC_OK_ERP != $data['code']){
            Log::error('erp_getOrgNameInfo Fail!');
            EC::fail($data['code']);
        }
        Log::notice("response-data =============OrgName==============>> data = ##" . json_encode($data) . "##" );
    
        EC::success(EC_OK, $data['data']['data']);
    }
    
    public function test_sendTransferTrade(){
        $spdBank_model = $this->model('spdBank');
    
        $params = array();
        
        $params['payerVirAcctNo'] = '62250806009'; // 付款人虚账号
        $params['payerName'] = '刘新辉'; // 付款人名称
        
        $params['payeeAcctNo'] = '6223635001004485218'; // 收款人账号
        $params['payeeAcctName'] = '钟煦镠'; // 收款人中文名
        
        $params['ownItBankFlag'] = '1';// 本行/它行标志 0：表示本行 1：表示它行
        $params['remitLocation'] = '1'; // 同城异地标志 0：同城 1：异地 跨行转账时必须输入(即本行/它行标志为1：表示它行)
        $params['payeeBankName'] = '珠海华润银行股份有限公司清算中心'; // 收款行名称 跨行转账时必须输入(即本行/它行标志为1：表示它行)
        $params['payeeBankAddress'] = '珠海华润银行股份有限公司清算中心'; // 收款行地址 跨行转账时必须输入(即本行/它行标志为1：表示它行)
        $params['payeeBankNo'] = '313585000990'; // 支付号 【收款账号行号】
        
        $params['transAmount'] = '1.5'; // 交易金额
        $params['note'] = '测试虚账户付款'; // 附言 如果跨行转账，附言请不要超过42字节（汉字21个）
        
        $data = $spdBank_model->sendTransferTrade($params);
        Log::notice("response-data ===========test_sendTransferTrade================>> data = ##" . json_encode($data) . "##" );
        $data = $data['body'];
        EC::success(EC_OK, $data);
    }
    
}