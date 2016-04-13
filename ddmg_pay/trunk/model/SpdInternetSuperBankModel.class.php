<?php

class SpdInternetSuperBankModel extends CurlModel{
	
	public function getInfo($params = array()) {
		return self::sendRequest('spdInternetSuperBank/getInfo',$params);
	}
	
	public function getList($params = array()) {
		return self::sendRequest('spdInternetSuperBank/getList',$params);
	}

}