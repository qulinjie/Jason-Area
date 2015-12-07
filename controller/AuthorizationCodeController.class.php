<?php
/**
 * 授权码 
 * @author zhangkui
 *
 */
class AuthorizationCodeController extends Controller {

    public function handle($params = array()) {
        Log::notice('AuthorizationCodeController  ==== >>> params=' . json_encode($params));
        if (empty($params)) {
            Log::error('AuthorizationCodeController . params is empty . ');
            EC::fail(EC_MTD_NON);
        } else {
            switch ($params[0]) {
                case 'getIndex':
                    $this->searchList(true);
                    break;
                case 'searchList':
                    $this->searchList();
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
        
        Log::notice('searchList--------------------->>> page=' . $current_page);
        if(!$current_page) {
            $current_page = 1;
        }
        
        $code_model = $this->model('authorizationCode');
        $params  = array();
        
        if($code) {
            $params['code'] = $code;
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
        
        if($current_page > $total_page) {
            $current_page = $total_page;
        } else if( 0 >= $current_page){
            $current_page = 1;
        }
        
        $params['current_page'] = $current_page;
        $params['page_count'] = $page_cnt;
        $data = $code_model->searchList($params, $current_page, $page_cnt);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }
        
        Log::notice("=====================>>> current_page=" . $current_page . ',total_page=' . $total_page );
        $data_list = $data['data'];
        $entity_list_html = $this->render('authorizationCode_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('authorizationCode', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'authorizationCode', 'authorizationCode_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
    
}