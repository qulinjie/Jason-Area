<?php
/**
 * @author zhangkui
 *
 */
class BcsMarketController extends BaseController {

    public function handle($params = array()) {
        Log::notice('BcsMarketController  ==== >>> params=' . json_encode($params));
        if (empty($params)) {
            Log::error('Controller . params is empty . ');
            EC::fail(EC_MTD_NON);
        } else {
            switch ($params[0]) {
                case 'loadInfo':
                    $this->loadInfo();
                    break;
                case 'getInfo':
                    $this->getInfo();
                    break;
                case 'loadChildAccount':
                    $this->loadChildAccount();
                    break;
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }
    
    
    protected function getInfo() {
        $bcsMarket_model = $this->model('bcsMarket');
    
        $params  = array();
        $params['id'] = 1; // TODO 
    
        $data = $bcsMarket_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
    
        $data_info = $data['data'][0];
        $view_html = $this->render('bcsMarketInfo', array('item' => $data_info), true);
        $this->render('index', array('page_type' => 'bcsMarket', 'bcsMarket_html' => $view_html));
    }
    
    protected function loadInfo() {
        $bcsBank_model = $this->model('bank');
        $bcsMarket_model = $this->model('bcsMarket');
        $conf = $this->getConfig('conf');
    
        $user_id = self::getCurrentUserId();
        $mch_no = $conf['MCH_NO'];
    
        $params  = array();
        $params['id'] = 1; //TODO 
        
        /**
         * 调用接口，查询 市场信息
        */
        $bcs_data = $bcsBank_model->getMarketBasicInfo( $mch_no );
        Log::notice('loadInfo ==== >>> getMarketBasicInfo response=##' . json_encode($bcs_data) . '##');
        if(false == $bcs_data || !empty($bcs_data['code'])){
            Log::error("getMarketBasicInfo failed . ");
            EC::fail($bcs_data['code']);
        }
        $bcs_data = $bcs_data['Body']['Response'];
        
        $params['MCH_NO'] = $bcs_data['MCH_NO']; // 商户编号
        $params['MCH_NAME'] = $bcs_data['MCH_NAME']; // 商户名称
        $params['MCH_PHONE'] = $bcs_data['MCH_PHONE']; // 商户联系电话
        $params['MCH_ADDRES'] = $bcs_data['MCH_ADDRES']; // 商户地址
        $params['MCH_CREATEDATE'] = $bcs_data['MCH_CREATEDATE']; // 商户创建日期
        $params['MCH_ACCOUNT_NO'] = $bcs_data['MCH_ACCOUNT_NO']; // 商户资总账号
        $params['MCH_ACCOUNT_NAME'] = $bcs_data['MCH_ACCOUNT_NAME']; // 商户资总账户名称
    
        if(empty($params['MCH_NO']) || empty($params['MCH_NAME'])) {
            Log::error("getCustomerInfo failed [MCH_NO，MCH_NAME] is empty . ");
            EC::fail($bcs_data['code']);
        }
    
        /**
         * 更新 市场信息
         */
        $upd_data = $bcsMarket_model->update($params);
        if(EC_OK != $upd_data['code']){
            Log::error("update failed . ");
            EC::fail($upd_data['code']);
        }
    
        Log::notice('loadInfo ==== >>> upd_data=' . json_encode($upd_data) );
        EC::success(EC_OK);
    }
    
    protected function loadChildAccount() {
        $bcsBank_model = $this->model('bank');
        $bcsMarket_model = $this->model('bcsMarket');
        $conf = $this->getConfig('conf');
    
        $user_id = self::getCurrentUserId();
        $mch_no = $conf['MCH_NO'];
    
        $params  = array();
        $params['id'] = 1; //TODO
    
        /**
         * 调用接口，查询 市场信息
         */
        $bcs_data = $bcsBank_model->getMarketInfo( $mch_no );
        Log::notice('loadChildAccount ==== >>> getMarketInfo response=##' . json_encode($bcs_data) . '##');
        if(false == $bcs_data || !empty($bcs_data['code'])){
            Log::error("getMarketInfo failed . ");
            EC::fail($bcs_data['code']);
        }
//         $bcs_data = $bcs_data['Body']['Response'];
    
//         $params['MCH_NO'] = $bcs_data['MCH_NO']; // 商户编号
//         $params['MCH_NAME'] = $bcs_data['MCH_NAME']; // 商户名称
//         $params['MCH_PHONE'] = $bcs_data['MCH_PHONE']; // 商户联系电话
//         $params['MCH_ADDRES'] = $bcs_data['MCH_ADDRES']; // 商户地址
//         $params['MCH_CREATEDATE'] = $bcs_data['MCH_CREATEDATE']; // 商户创建日期
//         $params['MCH_ACCOUNT_NO'] = $bcs_data['MCH_ACCOUNT_NO']; // 商户资总账号
//         $params['MCH_ACCOUNT_NAME'] = $bcs_data['MCH_ACCOUNT_NAME']; // 商户资总账户名称
    
//         if(empty($params['MCH_NO']) || empty($params['MCH_NAME'])) {
//             Log::error("getCustomerInfo failed [MCH_NO，MCH_NAME] is empty . ");
//             EC::fail($bcs_data['code']);
//         }
    
//         /**
//          * 更新 市场信息
//          */
//         $upd_data = $bcsMarket_model->update($params);
//         if(EC_OK != $upd_data['code']){
//             Log::error("update failed . ");
//             EC::fail($upd_data['code']);
//         }
    
//         Log::notice('loadInfo ==== >>> upd_data=' . json_encode($upd_data) );
        EC::success(EC_OK);
    }
    
}