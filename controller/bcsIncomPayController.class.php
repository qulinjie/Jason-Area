<?php

//银行收付款明细
class BcsIncomPayController extends BaseController
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
        //只支持按席位号查询
        $params = array(
            'SIT_NO'                => $this->post('SIT_NO'),
            'START_DATE'        => $this->post('START_DATE'),
            'END_DATE'           => $this->post('END_DATE')
        );
        
        foreach ($params as $key => $val){
            if($val === ''){
                unset($params[$key]);
            }
        }

        $params['PAGE_SIZE']         = 10;
        $params['PAGE_NUMBER']  = $this->post('PAGE_NUMBER',1);
        $params['MCH_NO']           = $this->getMCH_NO();
      
        $bcs_data = $this->model('bank')->customerIncomePayQuery(1,$params);       
        if ($bcs_data['code'] !== 0) {
            Log::bcsError('getInflow error code(' . $bcs_data['code'] . ')' . ' msg：' . $bcs_data['msg']);
            EC::fail(EC_OTH);
        }
        
        Log::notice('bcsInflow<<<<'.var_export($bcs_data));
        $bcsIncomPay_list_html = $this->render('bcsIncomPay_list',array('data' => $bcs_data['data']),true);
       
        if($isSearch){
            EC::success(EC_OK,$bcsIncomPay_list_html);
        }
        
        $bcsIncomPay_html = $this->render('bcsIncomPay',array('bcsIncomPay_list_html' => $bcsIncomPay_list_html),true);      
        $this->render('index', array(   'page_type' => 'bcsIncomPay',  'bcsIncomPay_html' => $bcsIncomPay_html ));
    }
}