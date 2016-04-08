<?php


class PayPasswordModel extends CurlModel
{
    /**
     * 检查当前用户是否设置支付密码
     * @return bool|string
     */
    public function check()
    {
        return self::sendRequest('payPassword/check');
    }

    /**
     * 支付密码重置
     * @param array $params['payPassword',['oldPayPassword'..]]
     * @return bool|string
     */
    public function passwordReset($params = array())
    {
        return self::sendRequest('payPassword/passwordReset',$params);
    }

    /**
     * 判断输入的支付密码是否正确
     * @param array $params['payPassword']
     * @return bool|string
     */
    public function validatePassword($params = array())
    {
        return self::sendRequest('payPassword/validatePassword',$params);
    }
}