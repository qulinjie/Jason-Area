<?php
/**
 * 授权码 
 * @author zhangkui
 *
 */
class AuthorizationCodeController extends BaseController {

    public function handle($params = array()) {
        Log::notice('AuthorizationCodeController  ==== >>> params=' . json_encode($params));
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
                case 'changeStatus':
                    $this->changeStatus();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'create':
                    $this->create();
                    break;
                case 'getCode':
                    $this->getCode();
                    break;
                case 'checkCode':   // 不需要 登录
                    $this->checkCode();
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
        $code = Request::post('code');
        $time1 = Request::post('time1');
        $time2 = Request::post('time2');
        $type = Request::post('type');
        $status = Request::post('status');
        
        $code_model = $this->model('authorizationCode');
        $user_id = self::getCurrentUserId();
        
        $params  = array();
        foreach ([ 'user_id', 'code', 'time1', 'time2', 'type', 'status'] as $val){
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
        $entity_list_html = $this->render('authorizationCode_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('authorizationCode', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'authorizationCode', 'authorizationCode_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
 
    private function changeStatus(){
        $id = Request::post('id');
        $status = Request::post('status');
        
        Log::notice('changeStatus-Request ==== >>> id=' . $id . ',status=' . $status);
        if(!$id || !$status){
            Log::error('changeStatus params error!');
            EC::fail(EC_PAR_ERR);
        }
    
        $code_model = $this->model('authorizationCode');
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
        if( AuthorizationCodeModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
        if( AuthorizationCodeModel::$_status_overdue == $data_obj['status'] || $status == $data_obj['status'] ) {
            Log::error('record status is exception . status=' . $data_obj['status']);
            EC::fail(EC_RED_EXP);
        }
        
        $params['status'] = ($status == AuthorizationCodeModel::$_status_disabled) ? AuthorizationCodeModel::$_status_enabled : AuthorizationCodeModel::$_status_disabled;
        $params['disenabled_timestamp'] = ($status == AuthorizationCodeModel::$_status_disabled) ? '0000-00-00 00:00:00' : date('Y-m-d H:i:s',time()) ;
        
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
    
        $code_model = $this->model('authorizationCode');
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
        if( AuthorizationCodeModel::$_is_delete_true == $data_obj['is_delete'] ) {
            Log::error('record had delete . is_delete=' . $data_obj['is_delete']);
            EC::fail(EC_RED_EXP);
        }
        if( 0 < $data_obj['used_count'] ) {
            Log::error('record is exception . used_count=' . $data_obj['used_count']);
            EC::fail(EC_RED_EXP);
        }
        
        $params['is_delete'] = AuthorizationCodeModel::$_is_delete_true;
    
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
        
        $code_model = $this->model('authorizationCode');
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
        
        if(AuthorizationCodeModel::$_type_count == $type){
            $params['active_count'] = $active_count;
            $params['time_start'] = AuthorizationCodeModel::$_empyt_time;
            $params['time_end'] = AuthorizationCodeModel::$_empyt_time;
        } else if(AuthorizationCodeModel::$_type_time == $type){
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
    
    private function getCode(){
        $authCode = '';
        while(true) {
            $code_model = $this->model('authorizationCode');
            $authCode = $code_model->getRandChar(4) . rand(999,9999); //授权码生成规则：4字符+4个数字
            $data_authCode = $code_model->getInfo(array('code' => $authCode));
            if(EC_OK != $data_authCode['code']){
                Log::error('getInfo failed . code=' . $authCode);
                EC::fail($data_authCode['code']);
            }
            if ( empty($data_authCode['data']) ){
                Log::notice('getauthCode sucessed . code=' . $authCode);
                break;
            }
            Log::warning('code repetition . id=' . $data_authCode['data'][0]['id']);
        }
        EC::success(EC_OK,$authCode);
    }
    
    private function checkCode(){
        $authCode = Request::post('code');
        
        if(empty($authCode)){
            $post_data = self::getPostDataJson();
            if(!empty($post_data)) {
                $authCode = $post_data['data']['code'];
            }
        }
        
        if(!$authCode){
            Log::error('checkCode params error!');
            EC::fail(EC_PAR_ERR);
        }
        
        $code_model = $this->model('authorizationCode');
        $data_authCode = $code_model->getInfo(array('code' => $authCode));
        if(EC_OK != $data_authCode['code'] ){
            Log::error('getInfo failed . code=' . $authCode);
            EC::fail($data_authCode['code']);
        }
        if ( empty($data_authCode['data']) ){
            Log::notice('getauthCode sucessed . record is empty . code=' . $authCode);
            EC::success(EC_OK,"false");
        }
        
        $code_data = $data_authCode['data'][0];
        if(AuthorizationCodeModel::$_is_delete_true == $code_data['is_delete']) {
            Log::notice('code is delete . code=' . $authCode);
            EC::success(EC_OK,"delete");
        }
        if(AuthorizationCodeModel::$_status_disabled == $code_data['status']) {
            Log::notice('code is disabled . code=' . $authCode);
            EC::success(EC_OK,"disabled");
        } else if(AuthorizationCodeModel::$_status_overdue == $code_data['status']) {
            Log::notice('code is overdue . code=' . $authCode);
            EC::success(EC_OK,"overdue");
        } else if(AuthorizationCodeModel::$_status_enabled == $code_data['status']) {
            if($code_model->validataionCodeActive($code_data)){
                Log::notice('code is check OK . code=' . $authCode);
                EC::success(EC_OK,"true");
            } else {
                Log::notice('code had overdue . code=' . $authCode);
                EC::success(EC_OK,"overdue");
            }
        }
        Log::error('checkCode . code status is exception . code=' . $authCode . ',status=' . $code_data['status'] );
        EC::success(EC_OK,"false");
    }
    
}