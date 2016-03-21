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
    
    public function erp_login($params = array()){
        $url = CurlModel::getUrlErp('api/pub/userservice/PostUser_Login/');
        $base_data = [ 'caller'=>'ddmg_pay', 'callee'=>'ebLlyZDBSGgp', 'eventid'=>rand()%10000, 'timestamp'=>time() ];
        $base_data['data'] = $params;
        $base_data['url'] = $url;
        return self::sendRequest('admin/erp_login', $base_data); // 由 payapi 访问 erp 接口。
    }
    
}