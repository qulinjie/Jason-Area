<?php
class TradeRecordController extends Controller {

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
                case 'changeStatus':
                    $this->changeStatus();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'create':  // 不需要 登录
                    $this->create();
                    break;
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }
    
    private function create(){
        EC::success(EC_OK);
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
        $user_id = AuthorizationCodeController::getCurrentUserId();
    
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
    
}