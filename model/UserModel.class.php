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

    public function create($params = array()){
        return self::sendRequest('user/create', $params);
    }
    
    public function delete($params = array())
    {
        return self::sendRequest('user/delete',$params);
    }

    
    //////////
    public function login($params = array()){
        return self::sendRequest('user/login', $params);
    }

    public function logout($params = array()){
        return self::sendRequest('user/loginOut', $params);
    }

    public function getLoginUser(){
        return self::sendRequest('user/getLoginUser');
    }
    
    public function erp_login($params = array()){
        $url = CurlModel::getUrlErp('api/pub/userservice/PostUser_Login/');
        $base_data = [ 'caller'=>'ddmg_pay', 'callee'=>'ebLlyZDBSGgp', 'eventid'=>rand(1000,9999), 'timestamp'=>time() ];
        $base_data['data'] = $params;
        $base_data['url'] = $url;
        return self::sendRequest('user/erp_login', $base_data); // 由 payapi 访问 erp 接口。
    }
    
    public function erp_getList($params = array()){
        return self::sendRequestErp('api/pub/userservice/PostUser_GetList/',$params);
    }
    
    public function erp_getInfo($params = array()){
        return self::sendRequestErp('api/pub/userservice/PostUser_GetInfo/',$params);
    }
    
    public function erp_getContactCompanyInfo($params = array()){
    	return self::sendRequestErp('api/pub/ErpService/PostERP_wldw/',$params);
    }
    
    public function erp_getContactDeptInfo($params = array()){
    	return self::sendRequestErp('api/pub/ErpService/PostERP_bm/',$params);
    }
    
    public function erp_payPwdSet($params = array()){
    	return self::sendRequestErp('api/pub/userservice/PostUser_SetPayPwd/',$params);
    }
    
    public function erp_payPwdReset($params = array()){
    	return self::sendRequestErp('api/pub/userservice/PostUser_FindPayPwd/',$params);
    }
    
    public function erp_payPwdVerify($params = array()){
    	return self::sendRequestErp('api/pub/userservice/PostUser_ValidatePayPwd/',$params);
    }
    
}