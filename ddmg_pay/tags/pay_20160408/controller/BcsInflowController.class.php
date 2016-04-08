<?php

//银行出入金详情
class BcsInflowController extends BaseController
{
    public function __construct(){
        parent::__construct();
        if (!AdminController::isLogin()) {
            Log::error('admin not login');
            EC::fail(EC_NOT_LOGIN);
        }
    }
    
    public function handle($params = array())
    {
        switch ($params[0]) {
            case 'getIndex':
                $this->search();
                break;
            case 'searchList':
                $this->search(true);
                break;
            default:
                Log::error('method not exist : ' . $params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    private function search($isSearch = false)
    {
        if(!$isSearch){
            $bcsInflow_html = $this->render('bcsInflow',array('bcsInflow_list_html' => ''),true);
            return $this->render('index', array(  'page_type' => 'bcsInflow', 'bcsInflow_html' => $bcsInflow_html ));
        }
        
        $params = array(
            'SIT_NO'                => $this->post('SIT_NO'),
            'START_DATE'        => $this->post('START_DATE'),
            'END_DATE'           => $this->post('END_DATE'),
            'MCH_TRANS_NO' => $this->post('MCH_TRANS_NO')
        );
        
        foreach ($params as $key => $val){
            if($val === ''){
                unset($params[$key]);
            }
        }

        $params['PAGE_SIZE']         = 10;
        $params['PAGE_NUMBER']  = $this->post('PAGE_NUMBER',1);
        $params['MCH_NO']           = $this->getConfig('conf')['MCH_NO'];
      
        Log::error('request data<<<'.var_export($params,true));
        $bcs_data = $this->model('bank')->customerInflowQuery($params);       
        if ($bcs_data['code'] !== 0) {
            Log::bcsError('bcsInflow error code(' . $bcs_data['code'] . ')' . ' msg：' . $bcs_data['msg']);
            EC::fail(EC_OTH);
        }
        
        if($bcs_data['data']['List'] && !isset($bcs_data['data']['List'][0])){
            $bcs_data['data']['List'] = array($bcs_data['data']['List']);
        }
        
        $bcsInflow_list_html = $this->render('bcsInflow_list',array('data' => $bcs_data['data']),true);

        EC::success(EC_OK,array('bcsInflow_list_html' => $bcsInflow_list_html ));
    }
}