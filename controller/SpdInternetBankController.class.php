<?php
/**
 *
 */
class SpdInternetBankController extends BaseController {

    public function handle($params = array()) {
        Log::notice('SpdInternetBankController  ==== >>> params=' . json_encode($params));
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
                case 'getApplyIndex':
                    $this->searchList(true, '1');
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
    
    protected function searchList($isIndex = false, $isApplyIndex = '0') {
        
        if($isIndex && strval($isApplyIndex) == '1'){
            $entity_list_html = $this->render('spdInternetBank_list', array('data_list' => [], 'isApplyIndex' => $isApplyIndex, 'current_page' => 1, 'total_page' => 0), true);
            $apply_list_html = $this->render('spdInternetBank', array('entity_list_html' => $entity_list_html, 'isApplyIndex' => $isApplyIndex), true);
            EC::success(EC_OK, array('entity_list_html' => $apply_list_html));
        }
        
        $current_page = Request::post('page');
        $bankName = Request::post('bankName');
        $bankNo = Request::post('bankNo');        
        $super_bank_id = Request::post('super_bank_id');
        $city_id = Request::post('city_id');
        $isApplyIndex = !empty(Request::post('isApplyIndex')) ? Request::post('isApplyIndex') : $isApplyIndex;
        
        $code_model = $this->model('spdInternetBank');
        $user_id = self::getCurrentUserId();
        
        $params  = array();
        foreach ([ 'bankName', 'bankNo', 'super_bank_id', 'city_id' ] as $val){
            if($$val) $params[$val] = $$val;
        }
        if(isset($params['super_bank_id']) && $params['super_bank_id'] == '-1'){
        	unset($params['super_bank_id']);
        }
        if(isset($params['city_id']) && $params['city_id'] == '-1'){
        	unset($params['city_id']);
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
        
        Log::notice("---response--- searchList--- data=". json_encode($data));
        
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
        
        $data_list = $data['data'];
        
        /* foreach ($data_list as $k_data => $v_data){
        	$data_list[$k_data]['city_name'] = 
        } */        
        
        $entity_list_html = $this->render('spdInternetBank_list', array('data_list' => $data_list, 'isApplyIndex' => $isApplyIndex, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if(strval($isApplyIndex) == '1'){
        	$apply_list_html = $this->render('spdInternetBank', array('entity_list_html' => $entity_list_html, 'isApplyIndex' => $isApplyIndex), true);
        	EC::success(EC_OK, array('entity_list_html' => $apply_list_html));
        }else if(strval($isApplyIndex) == '2'){
        	EC::success(EC_OK, array('entity_list_html' => $entity_list_html, 'isApplyIndex' => $isApplyIndex));        	 
        }else if($isIndex) {
            $view_html = $this->render('spdInternetBank', array('entity_list_html' => $entity_list_html, 'isApplyIndex' => $isApplyIndex ), true);
            $this->render('index', array('page_type' => 'spdInternetBank', 'spdInternetBank_html' => $view_html));
        }else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html, 'isApplyIndex' => $isApplyIndex));
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
    
    private function changeStatus(){
        $id = Request::post('id');
        $status = Request::post('status');
        
        Log::notice('changeStatus-Request ==== >>> id=' . $id . ',status=' . $status);
        if(!$id || !$status){
            Log::error('changeStatus params error!');
            EC::fail(EC_PAR_ERR);
        }
    
        $code_model = $this->model('spdInternetBank');
        $user_id = self::getCurrentUserId();
    
        $params = array();
        $params['id'] = $id;
        $params['user_id'] = $user_id;
        
        if(empty($params)){
            Log::error('update params is empty!');
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
        if( SpdInternetBankModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
        if( SpdInternetBankModel::$_status_overdue == $data_obj['status'] || $status == $data_obj['status'] ) {
            Log::error('record status is exception . status=' . $data_obj['status']);
            EC::fail(EC_RED_EXP);
        }
        
        $params['status'] = ($status == SpdInternetBankModel::$_status_disabled) ? SpdInternetBankModel::$_status_enabled : SpdInternetBankModel::$_status_disabled;
        $params['disenabled_timestamp'] = ($status == SpdInternetBankModel::$_status_disabled) ? '0000-00-00 00:00:00' : date('Y-m-d H:i:s',time()) ;
        
        Log::notice('changeStatus ==== >>> params=' . json_encode($params) );
        $data = $code_model->update($params);
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
    
        $code_model = $this->model('spdInternetBank');
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
        if( SpdInternetBankModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
        if( 0 < $data_obj['used_count'] ) {
            Log::error('record is exception . used_count=' . $data_obj['used_count']);
            EC::fail(EC_RED_EXP);
        }
        
        $params['is_delete'] = SpdInternetBankModel::$_is_delete_true;
    
        Log::notice('changeStatus-delete ==== >>> params=' . json_encode($params) );
        $data = $code_model->update($params);
        if(EC_OK != $data['code']){
            Log::error('update Fail!');
            EC::fail($data['code']);
        }
        EC::success(EC_OK);
    }
    
    private function create(){
    	
    	if(!AdminController::isAdmin()){
    		EC::fail(EC_USER_NO_AUTH);
    	}
    	
        $spdBank_model = $this->model('spdBank');
        
        $data = $spdBank_model->queryBankNumberByName(array());
        Log::notice("response-data ===========spdInternetBank================>> data = ##" . json_encode($data) . "##" );
        $data_lists = $data['body']['lists']['list'];
        
        $this->addSpdInternetBankList($data_lists);
        EC::success(EC_OK);
    }
    
    public function addSpdInternetBankList($data_lists = array()){
        if(empty($data_lists)){
            Log::notice("addSpdInternetBankList data_lists is empty . ");
            return ;
        }
        Log::notice("addSpdInternetBankList data_lists sieze is = " . count($data_lists) );
        
        $code_model = $this->model('spdInternetBank');
        
        Log::notice("addSpdInternetBankList delete data str. ");
        $data = $code_model->delete('1=1');
        if(EC_OK != $data['code']){
            Log::error('delete Fail!');
        }
        Log::notice("addSpdInternetBankList delete data end. ");
        
        foreach($data_lists as $obj ){
            $params = array();
            $params['serialNo'] = $obj['serialNo'];
            $params['bankName'] = $obj['bankName'];
            $params['bankNo'] = $obj['bankNo'];
            $data = $code_model->create($params);
            if(EC_OK != $data['code']){
                Log::error('create Fail!');
            }
        }
        
    }
    
}