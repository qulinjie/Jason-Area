<?php

class UserModel extends CurlModel
{
    public function searchCnt($params = array()){
        return self::sendRequest('user/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('user/searchList', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('user/getInfo', $params);
    }
    
    public function getList($params = array()){
        return self::sendRequest('user/getList',$params);
    }
    
    public function update($params = array()){
        return self::sendRequest('user/update',$params);
    }
    
    public function sendCmsCode($params = array()){
        return self::sendRequest('user/sendSmsCode', $params);
    }

    public function register($params = array()){
        return self::sendRequest('user/register', $params);
    }

    public function login($params = array()){
        return self::sendRequest('user/login', $params);
    }

    public function logout($params = array()){
        return self::sendRequest('user/loginOut', $params);
    }

    public function getLoginUser(){
        return self::sendRequest('user/getLoginUser');
    }
}