<?php


class MessageModel extends CurlModel
{
    public function getCnt()
    {
        return self::sendRequest('message/getCnt');
    }

    public function searchList($params = array())
    {
        return self::sendRequest('message/searchList',$params);
    }
}