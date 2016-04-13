<?php

class ProvinceModel extends CurlModel{
	
	public function getInfo($params = array()) {
		return self::sendRequest('province/getInfo',$params);
	}
	
	public function getList($params = array()) {
		return self::sendRequest('province/getList',$params);
	}

}