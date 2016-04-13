<?php

class CityController extends Controller {

    public function handle($params = array()) {
        Log::notice('CityController  ==== >>> params=' . json_encode($params));
        if (empty($params)) {
            Log::error('Controller . params is empty . ');
            EC::fail(EC_MTD_NON);
        } else {
            switch ($params[0]) { 
                case 'getCityByProviceId':
                    $this->getCityByProviceId();
                    break;                
                default:
                    Log::error('page not found . ' . $params[0]);
                    EC::fail(EC_MTD_NON);
                    break;
            }
        }
    }
            
    public function getCityByProviceId($province_id = NULL) {
        $province_id = ($province_id == NULL) ? Request::post('province_id') : $province_id;

        $city_model = $this->model('city');            
        $params = array();
        $params['province_id'] = $province_id;    
        $data = $city_model->getInfo($params);
        if(EC_OK != $data['code']){
            Log::error("getInfo failed . ");
            EC::fail($data['code']);
        }
        EC::success(EC_OK, $data['data']);
    }   
    
}