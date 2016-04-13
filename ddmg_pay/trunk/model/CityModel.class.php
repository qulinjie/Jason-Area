<?php

class CityModel extends CurlModel{

	public function getInfo($params = array()) {
        return self::sendRequest('city/getInfo',$params);
    }
    
    public function getList($params = array()) {
    	return self::sendRequest('city/getList',$params);
    }
    
}