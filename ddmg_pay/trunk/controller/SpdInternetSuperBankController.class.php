<?php

class SpdInternetSuperBankController extends Controller {

    public function handle($params = array()) {
        Log::notice('SpdInternetSuperBankController  ==== >>> params=' . json_encode($params));
        if (empty($params)) {
            Log::error('Controller . params is empty . ');
            EC::fail(EC_MTD_NON);
        } else {
            switch ($params[0]) { 
                case 'getSuperBankList':
                    $this->getSuperBankList();
                    break;                
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }

    private static $_super_bank_list = NULL;
    public static function getSuperBankList($is_ec = true) {
        
		if(NULL != self::$_super_bank_list){			
			if($is_ec) EC::success(EC_OK, self::$_super_bank_list);
			return self::$_super_bank_list;
		}
        $model = self::model('spdInternetSuperBank');
        $params = array();
        $data = $model->getList($params);
        if(EC_OK != $data['code']){
            Log::error("getList failed . ");
            EC::fail($data['code']);
        }
        self::$_super_bank_list = $data['data'];
        if($is_ec) EC::success(EC_OK, $data['data']);
        return self::$_super_bank_list;
    }   
    
}