<?php

class Callback
{

    public function __construct()
    {}

    public function request($param)
    {
        $logFileName = 'K:/' . date('Y-m-d') . '.log';
        $myfile = @fopen($logFileName, "w");
        fwrite($myfile, date('Y-m-d H:i:s',time()) . "request=##" . $param . "##\r\n");
        $result = array(
            'result' => 'success',
            'mag' => $param
        );
        $result['date'] = date('Y-m-d H:i:s',time());
        $result = json_encode($result);
        fwrite($myfile, date('Y-m-d H:i:s',time()) . " response=##" . $result . "##\r\n");
        fclose($myfile);
        return $result;
    }
    
}
