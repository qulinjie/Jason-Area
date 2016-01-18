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
        
        $code_model = $this->model('bcsTransfer');
        $user_id = null;
        if(!AdminController::isAdmin()){
            $user_id = self::getCurrentUserId();
        }
        
        $params  = array();
        foreach ([ 'user_id', 'SIT_NO', 'time1', 'time2', 'FMS_TRANS_NO', 'status'] as $val){
            if($$val) $params[$val] = $$val;
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
        $entity_list_html = $this->render('bcsTransfer_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('bcsTransfer', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'bcsTransfer', 'bcsTransfer_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
 
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