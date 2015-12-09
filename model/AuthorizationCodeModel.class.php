<?php

class AuthorizationCodeModel extends CurlModel {
   
    // 是否删除 1-正常 2-已删除
    public static $_is_delete_false = 1;
    public static $_is_delete_true = 2;
    
    // 状态 1-正常/启用 2-停用 3-失效
    public static $_status_enabled = 1;
    public static $_status_disabled = 2;
    public static $_status_overdue = 3;
    
    // 使用方式 1-按次数 2-按时间段
    public static $_type_count = 1;
    public static $_type_time = 2;
    
    public static $_empyt_time = '0000-00-00 00:00:00';
    
    public function searchCnt($params = array()){
        return self::sendRequest('authorizationCode/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('authorizationCode/searchList', $params);
    }
    
    public function update($params = array()){
        return self::sendRequest('authorizationCode/update', $params);
    }
    
    public function getInfo($params = array()){
        return self::sendRequest('authorizationCode/getInfo', $params);
    }
    
    public function create($params = array()){
        return self::sendRequest('authorizationCode/create', $params);
    }
    
    public function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNPQRSTUVWXYZ"; // 去掉字母“O”
        $max = strlen($strPol)-1;
        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
    
}
