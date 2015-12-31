<?php


class FindPasswordModel extends CurlModel
{
    /**
     * 检查是否存在该用户
     * @param array $params['tel']
     * @return bool|string
     */
    public function check($params = array())
    {
        return self::sendRequest('findPassword/check',$params);
    }

    /**
     * 身份验证
     * @param array $params
     * @return bool|string
     */
    public function identity($params = array())
    {
        return self::sendRequest('findPassword/identity',$params);
    }

    /**
     * 重置登录密码
     * @param array $params
     * @return bool|string
     */
    public function loginPasswordReset($params = array())
    {
        return self::sendRequest('findPassword/loginPasswordReset',$params);
    }
}