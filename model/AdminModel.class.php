<?php
class AdminModel extends CurlModel {
    
    public function login($params = array()){
        return self::sendRequest('admin/login', $params);
    }
    
    public function loginOut($params = array()){
        return self::sendRequest('admin/loginOut', $params);
    }
    
    public function isLogin($params = array()){
        return self::sendRequest('admin/isLogin', $params);
    }
    
    public function changePwd($params = array()){
        return self::sendRequest('admin/changePwd', $params);
    }
    
}