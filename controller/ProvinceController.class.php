<?php

class ProvinceController extends Controller {

    public function handle($params = array()) {
        Log::notice('ProvinceController  ==== >>> params=' . json_encode($params));
        if (empty($params)) {
            Log::error('Controller . params is empty . ');
            EC::fail(EC_MTD_NON);
        } else {
            switch ($params[0]) { 
                case 'getProviceList':
                    $this->getProviceList();
                    break;                
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }

    private static $_province_list = NULL;
    public static function getProviceList($is_ec = true) {
        
		if(NULL != self::$_province_list){			
			if($is_ec) EC::success(EC_OK, self::$_province_list);
			return self::$_province_list;
		}
        $province_model = self::model('province');
        $params = array();
        $data = $province_model->getList($params);
        if(EC_OK != $data['code']){
            Log::error("getList failed . ");
            EC::fail($data['code']);
        }
        self::$_province_list = $data['data'];
        if($is_ec) EC::success(EC_OK, $data['data']);
        return self::$_province_list;
    }   
    
}