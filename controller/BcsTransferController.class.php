<?php
/**
 * @author zhangkui
 *
 */
class BcsTransferController extends BaseController {

    public function handle($params = array()) {
        Log::notice('BcsTransferController  ==== >>> params=' . json_encode($params));
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
                case 'loadInfo':
                    $this->loadInfo();
                    break;
                case 'delete':
                    $this->delete();
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
        $SIT_NO = Request::post('SIT_NO');
        $FMS_TRANS_NO = Request::post('FMS_TRANS_NO');
        $status = Request::post('status');
        $account = Request::post('account');
        
        $code_model = $this->model('bcsTransfer');
        $user_model = $this->model('user');
        $user_id = null;
        $user_id_list = array();
        if(AdminController::isAdmin()){
            if($account && !empty($account)){
                $user_data = $user_model->searchList(array('account' => $account));
                if(EC_OK != $user_data['code']){
                    Log::error("searchList failed . ");
                } else {
                    $user_data = $user_data['data'];
                    if(empty($user_data)){
                        Log::notice("searchList is empty . ");
                        EC::success(EC_OK, array('entity_list_html' => $this->render('bcsTransfer_list', array(), true) ));
                    } else {
                        foreach ($user_data as $key => $val){
                            $user_id_list[] = $user_data[$key]['id'];
                        }
                    }
                }
            }
        } else {
            $user_id = self::getCurrentUserId();
        }
        
        $params  = array();
        foreach ([ 'user_id', 'SIT_NO', 'time1', 'time2', 'FMS_TRANS_NO', 'status'] as $val){
            if($$val) $params[$val] = $$val;
        }
        
        if( $user_id_list && !empty($user_id_list) ){
            $params['user_id_list'] = $user_id_list;
        }
        
        $data_cnt = $code_model->searchCnt($params);
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
        $data = $code_model->searchList($params, $current_page, $page_cnt);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
        
        $data_list = $data['data'];
        
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
        
        $entity_list_html = $this->render('bcsTransfer_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('bcsTransfer', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'bcsTransfer', 'bcsTransfer_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
 
    protected function loadInfo() {
        $id = Request::post('id');
        if(!$id){
            Log::error('loadInfo params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        $code_model = $this->model('bcsTransfer');
        
        $data = $code_model->getInfo(array('id'=>$id));
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
        
        $data_info = $data['data'][0];
        
        $MCH_TRANS_NO = $data_info['MCH_TRANS_NO']; // 商户交易流水号
        $FUNC_CODE = '0'; //0-出入金交易，1-冻结解冻交易，2-现货交易
        Log::notice('loadInfo-str---req_data==>> MCH_TRANS_NO=' . $MCH_TRANS_NO);
        $bcs_data = $this->model('bank')->transactionStatusQuery($MCH_TRANS_NO,$FUNC_CODE);
        Log::notice('loadInfo-end---req_data==>>' . var_export($bcs_data, true));
        
        $data = $bcs_data['data'];
        if(empty($data)){
            Log::error("loadInfo failed . msg=" . $bcs_data['code'] . $bcs_data['msg']);
            
            $params = array();
            $params['id'] = $id;
            $params['status'] = 2 ;     // 失败
            $params['TRANS_STS'] = 2 ;  // 失败
            $params['comment'] = $bcs_data['code'] . $bcs_data['msg'] ;
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
                $params['TRANS_STS'] = 1 ;  // 成功
                $params['comment'] = '交易成功' ;
            } else if( 2 == $TRANS_STS){
                $params['status'] = 1 ;     // 成功
                $params['TRANS_STS'] = 2 ;  // 失败
                $params['comment'] = '交易失败' ;
            } else if( 3 == $TRANS_STS){
                $params['status'] = 1 ;     // 成功
                $params['TRANS_STS'] = 3 ;  // 处理中
                $params['comment'] = '交易状态未知' ;
            } else if( 4 == $TRANS_STS){
                $params['status'] = 2 ;     // 失败
                $params['TRANS_STS'] = 2 ;  // 失败
                $params['comment'] = '未找到交易记录' ;
            }
            
            $data_upd = $code_model->update($params);
            if(EC_OK != $data_upd['code']){
                Log::error("update failed . " . $data_upd['code'] );
            }
        }
        
        EC::success(EC_OK, $data);
    }
    
    // disabled
    protected function getInfo() {
        $code = Request::post('code');
    
        $tradeRecord_model = $this->model('tradeRecord');
        $user_id = self::getCurrentUserId();
    
        $params  = array();
        foreach ([ 'user_id', 'code' ] as $val){
            if($$val) $params[$val] = $$val;
        }
    
        $data = $tradeRecord_model->searchList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
    
        $data_list = $data['data'];
        $entity_list_html = $this->render('tradeRecord_list', array('data_list' => $data_list), true);
        EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
    }
    
    // disabled
    private function delete(){
        $id = Request::post('id');
    
        if(!$id){
            Log::error('delete params error!');
            EC::fail(EC_PAR_ERR);
        }
    
        $code_model = $this->model('bcsTransfer');
        $user_id = self::getCurrentUserId();
    
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
    
        if(empty($params)){
            Log::error('delete params is empty!');
            EC::fail(EC_PAR_BAD);
        }
    
        $data_old = $code_model->getInfo($params);
        if(EC_OK != $data_old['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_old['code']);
        }
        $data_obj = $data_old['data'];
        if(empty($data_obj)) {
            Log::error('getInfo empty !');
            EC::fail(EC_RED_EMP);
        }
        if( BcsTransferModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
        if( 0 < $data_obj['used_count'] ) {
            Log::error('record is exception . used_count=' . $data_obj['used_count']);
            EC::fail(EC_RED_EXP);
        }
        
        $params['is_delete'] = BcsTransferModel::$_is_delete_true;
    
        Log::notice('changeStatus-delete ==== >>> params=' . json_encode($params) );
        $data = $code_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }
    
    // disabled
    private function create(){
        $code = Request::post('code');
        $type = Request::post('type');
        $active_count = Request::post('active_count');
        $time_start = Request::post('time_start');
        $time_end = Request::post('time_end');
        $comment = Request::post('comment');
    
        if(!$code || !$type){
            Log::error('create params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        $code_model = $this->model('bcsTransfer');
        $user_id = self::getCurrentUserId();
    
        $data_old = $code_model->getInfo(array('code' => $code));
        if(EC_OK != $data_old['code']){
            Log::error('getInfo Fail!');
            EC::fail($data_old['code']);
        }
        $data_obj = $data_old['data'];
        if(!empty($data_obj)) {
            Log::error('getInfo code is exists ! id=' . $data_obj['id']);
            EC::fail(EC_RED_EMP);
        }
    
        $params = array();
        $params['user_id'] = $user_id;
        $params['code'] = $code;
        $params['type'] = $type;
        $params['comment'] = $comment;
        
        if(BcsTransferModel::$_type_count == $type){
            $params['active_count'] = $active_count;
            $params['time_start'] = BcsTransferModel::$_empyt_time;
            $params['time_end'] = BcsTransferModel::$_empyt_time;
        } else if(BcsTransferModel::$_type_time == $type){
            $params['active_count'] = 0;
            $params['time_start'] = $time_start;
            $params['time_end'] = $time_end;
        } else {
            Log::error('create params error! type=' . $type);
            EC::fail(EC_PAR_ERR);
        }
        
        if(empty($params)){
            Log::error('create params is empty!');
            EC::fail(EC_PAR_BAD);
        }
    
        $data = $code_model->create($params);
        if(EC_OK != $data['code']){
            Log::error('create Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }
    
}