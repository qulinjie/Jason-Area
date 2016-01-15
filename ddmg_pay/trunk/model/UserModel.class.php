<?php


class UserModel extends CurlModel
{
    
    public function searchCnt($params = array()){
        return self::sendRequest('user/searchCnt', $params);
    }
    
    public function searchList($params = array()){
        return self::sendRequest('user/searchList', $params);
    }
    
    /**
     * @param array $params ['tel','type'{1:注册，2：找回密码}]
     * @return array
     */
    public function sendCmsCode($params = array())
    {
        return self::sendRequest('user/sendSmsCode', $params);
    }

    /**
     * @param array $params ['tel','pwd','code']
     * @return array
     */
    public function register($params = array())
    {
        return self::sendRequest('user/register', $params);
    }

    /**
     * @param array $params ['tel','pwd']
     * @return array
     */
    public function login($params = array())
    {
        return self::sendRequest('user/login', $params);
    }

    /**
     * @param array $params []
     * @return  array
     */
    public function logout($params = array())
    {
        return self::sendRequest('user/loginOut', $params);
    }

    /**
     * @param array $params['realName','filePath','fileName','id']
     * @return array
     */
    public function updatePersonalAuth($params = array())
    {
        return self::sendRequest('user/updatePersonalAuthInfo',$params);
    }

    /**
     * @param array $params['legalPerson','companyName','license','filePath','fileName','id']
     * @return array
     */
    public function updateCompanyAuth($params = array())
    {
        return self::sendRequest('user/updateCompanyAuthInfo',$params);
    }

    /**
     * @return array
     */
    public function isLogin()
    {
        return self::sendRequest('user/isLogin');
    }

    /**
     * @return array
     */
    public function getLoginUser()
    {
        return self::sendRequest('user/getLoginUser');
    }

    public function loginPasswordReset($params = array())
    {
        return self::sendRequest('user/loginPasswordReset',$params);
    }

    
    public function getUserBasicInfo()
    {
        return self::sendRequest('user/getUserBasicInfo');
    }
    
    public function getUserInfo($params = array())
    {
        return self::sendRequest('user/getUserInfo',$params);
    }
    
    public function isSetPayPassword()
    {
        return self::sendRequest('user/isSetPayPassword');
    }

    /**
     * @param array $params['payPassword']
     * @return array
     */
    public function setPayPassword($params = array())
    {
        return self::sendRequest('user/setPayPassword',$params);
    }

    /**
     * @param array $params['payPassword']
     * @return array
     */
    public function validatePayPassword($params = array())
    {
        return self::sendRequest('user/validatePayPassword',$params);
    }


    public function isAdmin($params = array())
    {
        return self::sendRequest('user/isAdmin',$params);
    }

    public function getCnt($params = array())
    {
        return self::sendRequest('user/getCnt',$params);
    }

    public function getList($params = array())
    {
        return self::sendRequest('user/getList',$params);
    }

    public function audit($params = array())
    {
        return self::sendRequest('user/audit',$params);
    }
}