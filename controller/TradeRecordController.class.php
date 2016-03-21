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
                case 'create_add':
                    $this->create_add($req_data);
                    break;
                    
                case 'erp_getOrderBuy':
                    $this->erp_getOrderBuy();
                    break;
                case 'erp_getOrderBuyInfo':
                    $this->erp_getOrderBuyInfo();
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
    
//         if($isIndex && !$order_status) {
//             $order_status = TradeRecordModel::$_status_waiting;
//         }
        
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
    
        $data_list = $data['data'] ? $data['data'] : [];
        $tradeRecordItem_model = $this->model('tradeRecordItem');
        
        foreach ($data_list as $key => $val){
            $data = $tradeRecordItem_model->searchList(array('trade_record_id' => $val['id']));
            if($data['code'] !== EC_OK){
                Log::error('tradeRecordItem searchList error');
            }            
            $data_list[$key]['list'] = $data['data'] ? $data['data'] : [];
        }
        
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
    
        $tradeRecord_model = $this->model('tradeRecord');
        $tradeRecordItem_model = $this->model('tradeRecordItem');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        $params['id'] = $id;
        if($isCheck){
            $params['seller_id'] = $user_id;
        } else {
            $params['user_id'] = $user_id;
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
        Log::notice('data_info-----------------------------------params==>>' . var_export($data_info, true));
        if($isCheck){
            $entity_list_html = $this->render('tradeCheck', array('item' => $data_info), true);
            EC::success(EC_OK, array('tradeRecord_check' => $entity_list_html));
        } else {
            $entity_list_html = $this->render('tradePay', array('item' => $data_info), true);
            EC::success(EC_OK, array('tradeRecord_pay' => $entity_list_html));
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
    
    private function createApply(){
        $data_info = array();
        
        $tradeRecord_model = $this->model('tradeRecord');
        $user_model = $this->model('user');
        
        $data = $tradeRecord_model->getNextId(array());
        Log::notice("response-data ========22===================>> data = ##" . json_encode($data) . "##" );
        if(EC_OK != $data['code']){
            Log::error('getNextId Fail!');
            EC::fail($data['code']);
        }
        
        $data_info['id'] = $data['data'];
        $data_info['today'] = date('Y-m-d',time());
        
//         $loginUser_data = UserController::getLoginUser();
//         Log::notice("response-data ========33===================>> loginUser_data = ##" . json_encode($loginUser_data) . "##" );
        
        $user_id = self::getCurrentUserId();
        $data = $user_model->erp_getInfo(array('usercode' => $user_id));
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
        $data_info['name'] = $loginUser_data['username'];
        
        $view_html = $this->render('tradeRecordCreate', array('data_info' => $data_info), true);
        $this->render('index', array('page_type' => 'tradeRecord', 'tradeRecordCreate_html' => $view_html) );
    }
    
    public function create_add(){
        $apply_item = Request::post('order_no_arr'); // 业务单号@;金额
        $apply_no = Request::post('apply_no'); // 申请单号
        $seller_name = Request::post('comp_name'); // 收款单位
        $comp_account = Request::post('comp_account'); // 收款账号
        $bank_name = Request::post('bank_name'); // 开户行
        $amount_type = Request::post('amount_type'); // 款项类别
        $useTodo = Request::post('use'); // 用途
        $comment = Request::post('comment'); // 备注
        
        // ["LDRK002-00000002@;102470.56","LDRK002-00000034@;118743.30"]
//         Log::notice("response-data ===========================>> data = ##" . json_encode($order_no_arr) . "##" );
//         exit;
        
        $tradeRecord_model = $this->model('tradeRecord');
        
        $loginUser_data = UserController::getLoginUser();
//         Log::notice("response-data ===========================>> data-loginUser_data = ##" . json_encode($loginUser_data) . "##" );
        
        $trade_record = array();
        $trade_record_item = array();
        
        $sum_amount = 0;
        $order_no_str = '';
        foreach ($apply_item as $itemKey => $itemVal){
            $arr = explode("@;",$itemVal);
            $v_order_no = $arr[0];
            $v_amount = floatval($arr[1]);
            $order_no_str = $order_no_str . ',' . $v_order_no;
            $sum_amount = $sum_amount + $v_amount;
            $trade_record_item[$v_order_no]['order_no'] = $apply_no;
            $trade_record_item[$v_order_no]['itme_no'] = $v_order_no;
            $trade_record_item[$v_order_no]['bid_amount'] = $v_amount;
            $trade_record_item[$v_order_no]['record_type'] = 2;
        }
//         Log::notice("response-data ===========================>> data-order_no_str = ##" . $order_no_str . "##" );

        $trade_record['item'] = $trade_record_item;
        
        $trade_record['user_id'] = $loginUser_data['usercode'];
        $trade_record['order_no'] = substr($order_no_str,1);
        $trade_record['order_bid_amount'] = $sum_amount;
        
        $trade_record['apply_no'] = $apply_no;
        $trade_record['seller_name'] = $seller_name;
        $trade_record['comp_account'] = $comp_account;
        $trade_record['bank_name'] = $bank_name;
        $trade_record['amount_type'] = $amount_type;
        $trade_record['useTodo'] = $useTodo;
        $trade_record['comment'] = $comment;
        $trade_record['record_type'] = 2;
        $trade_record['order_timestamp'] = date('Y-m-d',time());
        
//         Log::notice("response-data ===========================>> data = ##" . json_encode($trade_record) . "##" );
//         exit;
        
        $data = $tradeRecord_model->create_add($trade_record);
        if(EC_OK != $data['code']){
            Log::error('create Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
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
        
        $params = array();
        $params['page'] = $current_page;
        $params['rows'] = $page_cnt;
        $params['ksrq'] = $time1;
        $params['jzrq'] = $time2;
        $params['fphm'] = $fphm;
        
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
    
    public function erp_getOrderBuyInfo(){
        $fphm = Request::post('fphm');
        
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
        Log::notice("response-data ===========================>> data = ##" . json_encode($data) . "##" );
        
        EC::success(EC_OK, $data['data']);
    }
    
    
}