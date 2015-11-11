<?php

class Callback
{

    public function __construct()
    {}

    /*
    public function request2($param)
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
    */
    
    public function request($param)
    {
        //$logFileName = 'K:/' . date('Y-m-d') . '.log';
		$logFileName = '/alidata/www/ddmg_pay/log/' . date('Y-m-d') . '.log';
		
        $myfile = @fopen($logFileName, "w");
        date_default_timezone_set(PRC);
        fwrite($myfile, date('Y-m-d H:i:s',time()) . " request=##" . $param . "##\r\n");
        
        $result = "";
        $result .= "<Service>";
        $result .= "	<Header>";
        $result .= "		<RequestTime>" . date('Y-m-d H:i:s',time()) . "</RequestTime>";
        $result .= "	</Header>";
        $result .= "	<Body>";
        $result .= "		<Response>";
        $result .= "			<RESULT>SUCCESS</RESULT>";
        $result .= "			<MSG>成功</MSG>";
        $result .= "		</Response>";
        $result .= "	</Body>";
        $result .= "</Service>";
        
        fwrite($myfile, date('Y-m-d H:i:s',time()) . " response=##" . $result . "##\r\n");
        fclose($myfile);
        return $result;
    }
    
}
