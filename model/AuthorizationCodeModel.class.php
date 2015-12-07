<?php

class AuthorizationCodeModel extends CurlModel {
   
    public function searchCnt($params = array()){
        return self::sendRequest('authorizationCode/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('authorizationCode/searchList', $params);
    }
    
}
