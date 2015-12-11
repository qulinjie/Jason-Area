<?php


class UserModel extends CurlModel
{
    /**
     * @param array $params ['tel','type'{1:注册，2：找回密码}]
     * @return array
     */
    public function sendCmsCode($params = array())
    {
        return self::sendRequest('user/send_sms_code', $params);
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
        return self::sendRequest('user/login_out', $params);
    }

    public function personAuth($params = array())
    {
        return self::sendRequest('user/personAuth', $params);
    }

    /**
     * @param array $params['realName','filePath','fileName','id']
     * @return array
     */
    public function updatePersonalAuth($params = array())
    {
        return self::sendRequest('user/update_personal_auth_info',$params);
    }

    /**
     * @param array $params['legalPerson','companyName','license','filePath','fileName','id']
     * @return array
     */
    public function updateCompanyAuth($params = array())
    {
        return self::sendRequest('user/update_company_auth_info',$params);
    }

    /**
     * @return array
     */
    public function isLogin()
    {
        return self::sendRequest('user/is_login');
    }

    /**
     * @return array
     */
    public function getLoginUser()
    {
        return self::sendRequest('user/get_login_user');
    }
}