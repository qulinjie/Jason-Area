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
    
    public function validataionCodeActive($code_data){
        if(empty($code_data)) {
            Log::error("validataionCodeActive params is empty . ");
            return false;
        }
        try{
            $type = $code_data['type'];
            if( AuthorizationCodeModel::$_type_count == $type ){ // 按次数
                if( $code_data['used_count'] < $code_data['active_count'] ){ // 已使用次数 < 可用次数
                    return true;
                }
                Log::notice("AuthorizationCode had overdue-count . code=" . $code_data['code'] . ',id=' . $code_data['id']  );
                return false;
            } else if( AuthorizationCodeModel::$_type_time == $type ){ // 按时间段
                $time_start = strtotime($code_data['time_start']);
                $time_end = strtotime($code_data['time_end']);
                $curr_time = time();
                if( $time_start <= $curr_time && $curr_time <= $time_end ) { // 有效时间-开始 <= 当前时间  <= 有效时间-结束
                    return true;
                }
                Log::notice("AuthorizationCode had overdue-time . code=" . $code_data['code'] . ',id=' . $code_data['id']  );
                return false;
            }
        } catch (Exception $e) {
            Log::error("validataionCodeActive err . msg=" . $e->getMessage() );
            return false;
        }
        Log::error("validataionCodeActive . record status is exception . code=" . $code_data['code'] . ",id=" . $code_data['id']  );
        return false;
    }
}
