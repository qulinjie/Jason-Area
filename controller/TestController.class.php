<?php
class TestController extends BaseController
{
	protected static $client;

    public function handle( $params= [] )
    {
        /*
        $xml = "<Service><Header><ServiceCode>FMSCUST0005</ServiceCode><ChannelId></ChannelId><ExternalReference>517708544102</ExternalReference><OriginalChannelId></OriginalChannelId><OriginalReference></OriginalReference><RequestTime>20160120144656</RequestTime><TradeDate></TradeDate><Version></Version><RequestBranchCode></RequestBranchCode><RequestOperatorId></RequestOperatorId><RequestOperatorType></RequestOperatorType><TermType></TermType><TermNo></TermNo><RequestType></RequestType><Encrypt></Encrypt><SignData>1C896A86EC1FA400DC4B32CE5291FE4E294D2CD1A04FEF3F4C148B2DBB4C261DA382D6C4AF5C302A2FBAC12A46B73AE917220E7F93FB394324F445CF5BDC489AB67AF430D5AB23C43101686DD2800079C3C6CBBEA85611C467CB194002DC7856C9EA9F4FCA686A50C06BAD8C4A85FCDB69897691D6B6B6A3679B185176E00C6C4F7C8C3A71CC427DB847AE9768366D4802971FF351E342ED00146B94AD5096ACAD086D55D0D0CBAB59202D432D3FE67BED3D2686D7A13B6D4EC1295EE8B32D8C39791EFC40FE6A3A47309DF3BAF139C310338E6B3A81CEBAD556B71D9A1653F7C96C2F04DFBCE50CBDA4590ECD901182CF9633311DCE80CDE9859BD72F6D2ABD</SignData></Header><Body><Request><MCH_NO>8001529592</MCH_NO><SIT_NO>DDMG00212</SIT_NO><ACT_TIME>2016-01-20 14:46:56</ACT_TIME><ACCOUNT_NO>80015295920101010000001</ACCOUNT_NO></Request></Body></Service>";
        $soap = new SoapClient('http://120.25.1.102/ddmg_pay/services/JTService.php?wsdl');
        
         $result = $soap->__call('request', array($xml));//__call('request',[$xml]);
        var_dump($result); 
         $data = $this->model('bank')->getCustomerInfo('8001529592','DDMG00212');
        var_dump($data);
        exit; */
		if ( !$params ) {
			//$this->test();
			//$this->markTest();
			//$this->testGet();
			//$this->laiyifa();
		} else 
        switch( $params[0] )
        {
            case 'testSaop1':
                $this-> testSaop1();
                break;
            case 'testSaop2':
                $this-> testSaop2();
                break;
                
            case 'spd_sign':
                $this->spd_sign();
                break;
            case 'spd_5144':
                $this->spd_5144();
                break;
            case 'spd_4362':
                $this->spd_4362();
                break;
            case 'spd_4381':
                $this->spd_4381();
                break;
            case 'spd_4363':
                $this->spd_4363();
                break;
            case 'spd_4465':
                $this->spd_4465();
                break;
            case 'spd_4466':
                $this->spd_4466();
                break;
            case 'spd_5145':
                $this->spd_5145();
                break;
            case 'spd_4662':
                $this->spd_4662();
                break;
            case 'spd_4468':
                $this->spd_4468();
                break;
            case 'spd_4469':
                $this->spd_4469();
                break;
            case 'spd_5148':
                $this->spd_5148();
                break;
            case 'spd_EG48':
                $this->spd_EG48();
                break;
            case 'spd_8801':
                $this->spd_8801();
                break;
            case 'spd_8924':
                $this->spd_8924();
                break;
            case 'spd_EG30':
                $this->spd_EG30();
                break;
                
            case 'test_Login':
                $this->test_Login();
                break;
            case 'test_GetList':
                $this->test_GetList();
                break;
            case 'test_SKDCreate':
                $this->test_SKDCreate();
                break;
            case 'test_wldw':
                $this->test_wldw();
                break;
            case 'test_sendsmscode':
                $this->test_sendsmscode();
                break;
                
                
            case 'test_self':
                $this->test_self();
                break;
            case 'test_self_send':
                $this->test_self_send();
                break;
            default:
                Log::error('page not found');
                EC::page_not_found();
                break;
        }
    }

    
    // str-SPD
    
    //5144虚账户母子关系登记薄查询
    public function spd_5144(){
        Log::notice('-------SPD----TestController--------------------spd_5144==>>str');
        
        $model = $this->model('test');
        
        //1
        //$param = '<body><acctNo>6224080602781</acctNo><virtualAcctNo></virtualAcctNo><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
        //$param = '<body><acctNo>6224080600234</acctNo><virtualAcctNo>62250806009</virtualAcctNo><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
        $param = '<body><acctNo>66100155260001330</acctNo><virtualAcctNo></virtualAcctNo><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
        
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>" 
                    . "<transCode>5144</transCode><signFlag>1</signFlag>" 
//                     . "<masterID>2000040752</masterID><packetID>"
                    . "<masterID>2012541443</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                    . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                    . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
        
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
        
        Log::notice('-------SPD----TestController---------------------spd_5144==>>end');
        
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function spd_4362(){
        Log::notice('-------SPD----TestController--------------------spd_4362==>>str');
    
        $model = $this->model('test');
        
//         $masterName_unistr = $this->unicode_encode("令狐冲", 'UTF-8', "\\u", '');
//         Log::notice("\r\n\r\n ============000================\r\n\r\n masterName_unistr令狐冲=##" . $masterName_unistr . "##" . iconv('UTF-8', 'GB2312', "令狐冲"));
//         exit;
        
        //1
        //$param = '<body><acctNo>6224080602781</acctNo><lists name="LoopResult"><list><masterName>大汉长沙张三</masterName><virtualAcctNo>62250806001</virtualAcctNo><rate>0</rate></list><list><masterName>大汉长沙李四</masterName><virtualAcctNo>62250806002</virtualAcctNo><rate>0</rate></list><list><masterName>大汉武汉王五</masterName><virtualAcctNo>62250806003</virtualAcctNo><rate>0</rate></list><list><masterName>大汉徐州周六</masterName><virtualAcctNo>62250806004</virtualAcctNo><rate>0</rate></list></lists></body>';
        //$param = '<body><acctNo>6224080600234</acctNo><lists name="LoopResult"><list><masterName>Test-ZhangKui</masterName><virtualAcctNo>62250806002</virtualAcctNo><rate>1</rate></list></lists></body>';
        //$param = '<body><acctNo>6224080600234</acctNo><lists name="LoopResult"><list><masterName>zhangkui</masterName><virtualAcctNo>62250806005</virtualAcctNo><rate>1</rate></list></lists></body>';
        //$param = '<body><acctNo>6224080600234</acctNo><lists name="LoopResult"><list><masterName>令狐冲</masterName><virtualAcctNo>62250806008</virtualAcctNo><rate>1</rate></list></lists></body>';
        
        $masterName = $this->iconvFunc("刘新辉");
        $param = '<body><acctNo>6224080600234</acctNo><lists name="LoopResult"><list><masterName>' . $masterName . '</masterName><virtualAcctNo>62250806009</virtualAcctNo><rate>1</rate></list></lists></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>4362</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_4362==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function spd_4381(){
        Log::notice('-------SPD----TestController--------------------spd_4381==>>str');
    
        $model = $this->model('test');
    
        //1
        //$param = '<body><acctNo>6224080602781</acctNo><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
        $param = '<body><acctNo>6224080600234</acctNo><beginNumber>21</beginNumber><queryNumber>20</queryNumber></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>4381</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
//         header('Content-type:Text/html;charset=UTF-8');
//         header('Content-type:Text/html;charset=GB2312');
//         var_export( json_encode($data['body']['sic']) );
//         var_export($data['body']['sic']);
//         exit;
        Log::notice('-------SPD----TestController---------------------spd_4381==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function spd_4363(){
        Log::notice('-------SPD----TestController--------------------spd_4363==>>str');
    
        $model = $this->model('test');
    
        //1
        $param = '<body><acctNo>6224080600234</acctNo><virtualAcctNo>62250806003</virtualAcctNo></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>4363</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_4363==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function spd_4465(){
        Log::notice('-------SPD----TestController--------------------spd_4465==>>str');
    
        $model = $this->model('test');
    
        //1
        $param = '<body><acctNo>6224080602781</acctNo><shareType>0</shareType><seqNos>999701040001</seqNos><jnlNoProduceDate>20160308</jnlNoProduceDate><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>4465</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_4465==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    // 4466母实子虚账户收入支出分摊
    public function spd_4466(){
        Log::notice('-------SPD----TestController--------------------spd_4466==>>str');
    
        $model = $this->model('test');
    
        //1
        //$param = '<body><acctNo>6224080602781</acctNo><jnlNoDate>20160309</jnlNoDate><seqNos>999701040001</seqNos><summonsNumber>3</summonsNumber><transAmount>1000</transAmount><debitCreditFlag>1</debitCreditFlag><shareRule>2</shareRule><shareType>1</shareType><summaryCode></summaryCode><lists name="LoopResult"><list><virtualAcctNo>12345678901</virtualAcctNo><transAmount>1000</transAmount></list></lists></body>';
        //$param = '<body><acctNo>6224080600234</acctNo><jnlNoDate>20160314</jnlNoDate><seqNos>999701220001</seqNos><summonsNumber>3</summonsNumber><transAmount>112</transAmount><debitCreditFlag>1</debitCreditFlag><shareRule>2</shareRule><shareType>1</shareType><summaryCode></summaryCode><lists name="LoopResult"><list><virtualAcctNo>62250806009</virtualAcctNo><transAmount>112</transAmount></list></lists></body>';
        //$param = '<body><acctNo>6224080600234</acctNo><jnlNoDate>20160322</jnlNoDate><seqNos>999701590001</seqNos><summonsNumber>3</summonsNumber><transAmount>1000.23</transAmount><debitCreditFlag>1</debitCreditFlag><shareRule>2</shareRule><shareType>1</shareType><summaryCode></summaryCode><lists name="LoopResult"><list><virtualAcctNo>62250806009</virtualAcctNo><transAmount>1000.23</transAmount></list></lists></body>';
        $param = '<body><acctNo>6224080600234</acctNo><jnlNoDate>' . date('Ymd',time()) .'</jnlNoDate><seqNos>999701600002</seqNos><summonsNumber>3</summonsNumber><transAmount>231.5</transAmount><debitCreditFlag>1</debitCreditFlag><shareRule>2</shareRule><shareType>1</shareType><summaryCode></summaryCode><lists name="LoopResult"><list><virtualAcctNo>62250806009</virtualAcctNo><transAmount>231.5</transAmount></list></lists></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>4466</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_4466==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    // 5145虚账户已分摊交易明细查询
    public function spd_5145(){
        Log::notice('-------SPD----TestController--------------------spd_5145==>>str');
    
        $model = $this->model('test');
    
        //1
        //$param = '<body><acctNo>6224080602781</acctNo><virtualAcctNo>12345678901</virtualAcctNo><jnlSeqNo></jnlSeqNo><summonsNumber></summonsNumber><transBeginDate></transBeginDate><transEndDate></transEndDate><shareBeginDate>20160301</shareBeginDate><shareEndDate>20160330</shareEndDate><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
//         $param = '<body><acctNo>6224080600234</acctNo><virtualAcctNo>62250806009</virtualAcctNo><jnlSeqNo></jnlSeqNo><summonsNumber></summonsNumber><transBeginDate></transBeginDate><transEndDate></transEndDate><shareBeginDate>20160328</shareBeginDate><shareEndDate>20160330</shareEndDate><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
        $param = '<body><acctNo>66100155260001330</acctNo><virtualAcctNo>66106610888</virtualAcctNo><jnlSeqNo></jnlSeqNo><summonsNumber></summonsNumber><transBeginDate></transBeginDate><transEndDate></transEndDate><shareBeginDate>20160328</shareBeginDate><shareEndDate>20160331</shareEndDate><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>5145</transCode><signFlag>1</signFlag>"
//                 . "<masterID>2000040752</masterID><packetID>"
                    . "<masterID>2012541443</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_5145==>>end');
//         header('Content-type:Text/html;charset=utf-8');
//         var_export( $data );
//         exit;
        EC::success(EC_OK,$data['body']['sic']);
    }

    // 4662母账户未分摊明细查询
    public function spd_4662(){
        Log::notice('-------SPD----TestController--------------------spd_4662==>>str');
    
        $model = $this->model('test');
    
        //1
        //$param = '<body><acctNo>6224080602781</acctNo><beginDate>20160101</beginDate><endDate>20160130</endDate><beginNumber>1</beginNumber><queryNumber>5</queryNumber></body>';
        //$param = '<body><acctNo>6224080602781</acctNo><beginDate>20160309</beginDate><endDate>20160309</endDate><beginNumber>1</beginNumber><queryNumber>15</queryNumber></body>';
        $param = '<body><acctNo>6224080600234</acctNo><beginDate>' . date('Ymd',time()) .'</beginDate><endDate>' . date('Ymd',time()) . '</endDate><beginNumber>1</beginNumber><queryNumber>5</queryNumber></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>4662</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_4662==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function spd_4468(){
        Log::notice('-------SPD----TestController--------------------spd_4468==>>str');
    
        $model = $this->model('test');
    
        //1
        $param = '<body><acctNo>6224080600234</acctNo><beginNumber>41</beginNumber><queryNumber>20</queryNumber></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>4468</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_4468==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function spd_4469(){
        Log::notice('-------SPD----TestController--------------------spd_4469==>>str');
    
        $model = $this->model('test');
    
        //1
        $param = '<body><acctNo>6224080602781</acctNo><queryDate>20160104</queryDate><queryNumber>1</queryNumber><beginNumber>5</beginNumber></body>';
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>4469</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_4469==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function spd_5148(){
        Log::notice('测试-------SPD----TestController--------------------spd_5148==>>str');
        
        $model = $this->model('test');
        
        //1
        //$param = '<body><electronNumber></electronNumber><appointDate></appointDate><acctNo>6224080602781</acctNo><payerVirAcctNo>12345678901</payerVirAcctNo><payerName>浦发2000046127</payerName><payeeAcctType>0</payeeAcctType><payeeAcctNo>6223635001004485218</payeeAcctNo><payeeAcctName>钟煦镠 </payeeAcctName><payeeBankNo>313585000990</payeeBankNo><payeeBankName>珠海华润银行股份有限公司清算中心</payeeBankName><payeeBankAddress>珠海华润银行股份有限公司清算中心</payeeBankAddress><transAmount>1.2</transAmount><ownItBankFlag>1</ownItBankFlag><remitLocation>1</remitLocation><note>测试虚拟账户转账付款</note><payeeBankSelectFlag>1</payeeBankSelectFlag></body>';
        
        $payeeAcctName = $this->iconvFunc("钟煦镠");
        $payerName = $this->iconvFunc("浦发2000046127");
        $payeeBankName = $this->iconvFunc("珠海华润银行股份有限公司清算中心");
        $payeeBankName = $this->iconvFunc("珠海华润银行股份有限公司清算中心");
        $note = $this->iconvFunc("测试虚拟账户转账付款");
        
        $param = '<body><electronNumber></electronNumber><appointDate></appointDate><acctNo>6224080600234</acctNo><payerVirAcctNo>62250806009</payerVirAcctNo><payerName>' . $payerName . '</payerName><payeeAcctType>0</payeeAcctType><payeeAcctNo>6223635001004485218</payeeAcctNo><payeeAcctName>' . $payeeAcctName . '</payeeAcctName><payeeBankNo>313585000990</payeeBankNo><payeeBankName>' . $payeeBankName . '</payeeBankName><payeeBankAddress>' . $payeeBankName . '</payeeBankAddress><transAmount>12.5</transAmount><ownItBankFlag>1</ownItBankFlag><remitLocation>1</remitLocation><note>' . $note . '</note><payeeBankSelectFlag>1</payeeBankSelectFlag></body>';
        
        $data = $model->testSpdSign1($param);
        Log::notice("\r\n\r\n ============111================\r\n\r\n");
    
        //2
        $signature = strval( $data['body']['sign'] );
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head>"
            . "<transCode>5148</transCode><signFlag>1</signFlag>"
                . "<masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                        . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                            . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        $data = $model->testSpdSend1($param);
        Log::notice("\r\n\r\n ============222================\r\n\r\n");
    
        //3
        $param = strval( $data['body']['signature'] );
        $data = $model->testSpdSign2($param);
    
        Log::notice('-------SPD----TestController---------------------spd_5148==>>end');
    
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function spd_EG48(){
        Log::notice('-------SPD----TestController--------------------spd_EG48==>>str');
    
        $model = $this->model('spdBank');
    
        $bankName = $this->iconvFunc("珠海华润银行股份有限公司清算中心");
        
        //$param = '<body><bankName></bankName><bankNo></bankNo></body>';
        $param = '<body><bankName>' . $bankName . '</bankName><bankNo></bankNo></body>';
        $data = $model->curlSpdRequestXml($param,"EG48");
        
        Log::notice('-------SPD----TestController---------------------spd_EG48==>>end');
    
        EC::success(EC_OK,$data);
    }
    
    public function spd_8801(){
        Log::notice('-------SPD----TestController--------------------spd_8801==>>str');
    
        $model = $this->model('spdBank');
    
        $param = '<body><totalNumber>1</totalNumber><totalAmount>10</totalAmount><elecChequeNo>201637095051</elecChequeNo><acctNo>6224080400151</acctNo><acctName></acctName><bespeakDate></bespeakDate><payeeAcctNo>6224080602781</payeeAcctNo><payeeName></payeeName><payeeType>0</payeeType><payeeBankName></payeeBankName><payeeAddress></payeeAddress><amount>1.35</amount><sysFlag>0</sysFlag><remitLocation>0</remitLocation><note>大汉测试3</note><payeeBankSelectFlag>0</payeeBankSelectFlag><payeeBankNo></payeeBankNo></body>';
        $data = $model->curlSpdRequestXml($param,"8801");
    
        Log::notice('-------SPD----TestController---------------------spd_8801==>>end');
    
        EC::success(EC_OK,$data);
    }
    
    // 8924账户明细查询
    public function spd_8924(){
        Log::notice('-------SPD----TestController--------------------spd_8924==>>str');
    
        $model = $this->model('spdBank');
    
//         $param = '<body><acctNo>6224080600234</acctNo><beginDate>20160329</beginDate><endDate>20160329</endDate><queryNumber>20</queryNumber><beginNumber>1</beginNumber><transAmount></transAmount><subAccount></subAccount><subAcctName></subAcctName></body>';
        $param = '<body><acctNo>66100155260001330</acctNo><beginDate>20160331</beginDate><endDate>20160331</endDate><queryNumber>20</queryNumber><beginNumber>1</beginNumber><transAmount></transAmount><subAccount></subAccount><subAcctName></subAcctName></body>';
        $data = $model->curlSpdRequestXml($param,"8924");
    
        Log::notice('-------SPD----TestController---------------------spd_8924==>>end');
    
        EC::success(EC_OK,$data);
    }
    
    // EG30网银互联交易结果信息查询
    public function spd_EG30(){
        Log::notice('-------SPD----TestController--------------------spd_EG30==>>str');
    
        $model = $this->model('spdBank');
    
        $param = '<body><businessNo>SQ20161000037575</businessNo><electronNumber></electronNumber><directMasterID>2000040752</directMasterID></body>';
        $data = $model->curlSpdRequestXml($param,"EG30");
    
        Log::notice('-------SPD----TestController---------------------spd_EG30==>>end');
    
        EC::success(EC_OK,$data);
    }
    
    
    
    public function spd_sign(){
        Log::notice('-------SPD----TestController--------------------spd_sign==>>str');
    
        //1
        $model = $this->model('test');
        //$param = '<body><acctNo>2000040752</acctNo><virtualAcctNo></virtualAcctNo><beginNumber>1</beginNumber><queryNumber>20</queryNumber></body>';
        $param = '<body><lists name="acctList"><list><acctNo>6224080400151</acctNo></list></lists></body>';
        $data = $model->testSpdSign1($param);
        
        Log::notice('-------SPD----TestController---------------------spd_sign==>>end');
        //EC::success(EC_OK,strval($data));
//         Log::notice('======================>>> result= ##' . json_encode( $data, true ) .'##');
        
        //2
        $signature = strval( $data['body']['sign'] );
        Log::notice('======11================>>> signature= ##' . strval( $signature ) .'##');
        
        $param = "<?xml version='1.0' encoding='GB2312'?><packet><head><transCode>4402</transCode><signFlag>1</signFlag><masterID>2000040752</masterID><packetID>"
                    . date('YmdHis',time()) . "</packetID><timeStamp>"
                    . date('Y-m-d H:i:s',time()) . "</timeStamp></head><body><signature>"
                    . $signature  . "</signature></body></packet>";
        $param = (strlen($param) + 6) . '  ' . $param;
        
        Log::notice('======22================>>> param= ##' . strval( $param ) .'##');
        $data = $model->testSpdSend1($param);
        
        
        //3
        $param = strval( $data['body']['signature'] );
        Log::notice('======33================>>> param= ##' . strval( $param ) .'##');
        $data = $model->testSpdSign2($param);
        
        EC::success(EC_OK,$data['body']['sic']);
    }
    
    public function iconvFunc($param = ''){
        $result = iconv('UTF-8', 'GB2312', $param);
        if( empty($result) ) {
            $result = iconv('UTF-8', 'GBK', $param);
        }
        return $result;
    }
    
    // end-SPD
    
    
    public static $erp_url = 'http://test-api.gt-xx.com';
//     public static $erp_url = 'http://test-api.gt-xx.com'; // 
    public function test_Login(){
        $interface = self::$erp_url . "/api/pub/userservice/PostUser_Login/";
        $data = array();
        $data['loginid'] = '110002';
        $data['userpwd'] = '1';
        
        $model = $this->model('test');
        $data = $model->test_sendRequest($interface, $data);
        
        EC::success(EC_OK,$data);
    }
    
    public function test_GetList(){
        $interface = self::$erp_url . "/api/pub/userservice/PostUser_GetList/";
        $data = array();
        $data['is_paymanage'] = '2';
    
        $model = $this->model('test');
        $data = $model->test_sendRequest($interface, $data);
    
        EC::success(EC_OK,$data);
    }
    
    public function test_SKDCreate(){
        $interface = "http://test-api.gt-xx.com/api/pub/FinanceService/PostCW_SKDCreate/";
        $data = array();
        $data['name'] = '大汉电子商务有限公司';
    
        $model = $this->model('test');
        $data = $model->test_sendRequest($interface, $data);
    
        EC::success(EC_OK,$data);
    }
    
    public function test_wldw(){
        $interface = self::$erp_url . "/api/pub/ErpService/PostERP_wldw/";
        $data = array();
        //$data['dwmc'] = '大汉电子商务有限公司';
        $data['dwmc'] = '成都金万丰钢铁有限公司';
    
        $model = $this->model('test');
        $data = $model->test_sendRequest($interface, $data);
    
        EC::success(EC_OK,$data);
    }
    
    public function test_sendsmscode(){
        $interface = self::$erp_url . "/api/pub/KKunService/PostKKun_sendsmscode/";
        
        // 尊敬的客户，【Value1】已提交支付，支付【Value2】为【Value3】，请及时跟进。感谢您的支持【Value4】
        $data = array();
        $data['tel'] = '13265431549';
        $data['codetype'] = '10';
        
        $data['value1'] = '大汉电子商务有限公司 ';
        $data['value2'] = '金额';
        $data['value3'] = '23000元';
        $data['value4'] = '!';
        
        $model = $this->model('test');
        $data = $model->test_sendRequest($interface, $data);
    
        EC::success(EC_OK,$data);
    }
    
    
    

    
    public function testSaop1(){
        $model = Controller::instance( 'ErpSoap' );
        $data = $model->testForAddOrder();
        Log::error('TestController->testSaop1. data=' . $data);
        EC::success(EC_OK,$data);
    }
    
    public function testSaop2(){
        $model = Controller::instance( 'ErpSoap' );
        $data = $model->getCompanyList("http://124.232.142.207:8080/DhErpService/services/erpservice?wsdl","大汉物流股份有限公司");
        Log::error('TestController->testSaop2. data=' . $data);
        EC::success(EC_OK,$data);
    }

    
	public function testGet()
	{
		$ServiceCode = 'FMSCUST0002';
		$requestParms = ['MCH_NO'=>'198209'];
		//var_dump($ServiceCode);
		//$ServiceCode = 'FMSCUST0003';
		//$requestParms = ['MCH_NO'=>'198209', 'SIT_NO'=>'1'];
		$res = $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
		var_dump($res);
	}

	public function markTest()
	{
		$url = 'http://162.16.1.137:43294/icop/services/JTService?wsdl';
		//$url = 'http://120.25.1.102/ddmg_pay/services/JTService.php?wsdl';
		//$url = 'http://124.232.142.207:8080/DhErpService/services/erpservice?wsdl';
		$clien = new SoapClient( $url );
		$xmlStr = '<Service>  
					   <Header>   
						 <ProductId/> 
							<ServiceCode>FMSCUST0002</ServiceCode> 
							<ChannelId>607</ChannelId> 
							<ExternalReference>370000201408210006485</ExternalReference>
							<OriginalChannelId>002</OriginalChannelId>
							<OriginalReference>201408210006485</OriginalReference>
							<RequestTime>20150801110925</RequestTime>
							<TradeDate>20150518</TradeDate>
							<Version>1.0</Version>
							<RequestBranchCode>CN0010001</RequestBranchCode>
							<RequestOperatorId>FB.ICOP.X01</RequestOperatorId>
							<RequestOperatorType>0</RequestOperatorType>
							<TermType>00000</TermType>
							<TermNo>0000000000</TermNo>
							<RequestType>0</RequestType>
							<Encrypt>0</Encrypt>
					 </Header>   
					 <Body> 
						  <Request>   
						 <MCH_NO>198209</MCH_NO>   
						 </Request> 
					   </Body>
				 </Service>';

		/*
		$resArr = $this->xmlToArray( $xmlStr );
		$resXML = $this->arrayToXml( $resArr );
		var_dump($resArr);
		var_dump($this->xmlToArray($resXML));
		 */
		

		//var_dump(substr($this-> getExternalReference(), 0, 14));
		/*
		 *$data = [
		 *    'Service'=>[
		 *        'Header'=>[
		 *            'ProductId' => '', 
		 *            'ServiceCode' => 'FMSCUST0002', 
		 *            'ChannelId' => '607', 
		 *        ], 
		 *        'Body'=>[
		 *            'Request' => [
		 *                'MCH_NO' => '198209'
		 *            ], 
		 *        ], 
		 *    ]
		 *];
		 *$data = [
		 *        'MCH_NO'=>'198209', 
		 *        'MCH_Ne'=>'198209', 
		 *];
		 */
		//var_dump($data);
		//var_dump( $this->arrayToXml( $data ) );
		//var_dump( $this->constructBody( $data ) );

		$res = $clien->__soapCall( 'request', [$xmlStr] );
		$resArr = $this->xmlToArray( $res );
		var_dump($resArr);

		//new SoapVal(  );
		//var_dump($clien->__getFunctions());
		//var_dump($clien->__getTypes());
	}


	/**
	 * @brief:  发送信息
	 * @return:  
	 */
	public function sendQuery( $ServiceCode, $requestParms, $fetchAll=false )
	{
		$SendString = $this->getSendString( $ServiceCode, $requestParms );
		$client = $this-> getSoapClient();
		$resXMLString = $client->__soapCall( 'request', $SendString );
		return $this->fetchArrayResult( $resXMLString, $fetchAll );
		//return $this-> xmlToArray( $resXML );
	}

	private function fetchArrayResult( $resXMLString, $fetchAll=false )
	{
		$arrResult = $this->xmlToArray( $resXMLString );
		if ( $fetchAll ) {
			return $arrResult;
		}
		return $arrResult['Body']['Response'];
	}

	private function getSendString( $ServiceCode, $requestParms )
	{
		$bodyXmlString = $this-> constructBody( $requestParms );
		$headerXmlString = $this-> constructHeader( $ServiceCode, $bodyXmlString );
		return ["<Service>{$headerXmlString}{$bodyXmlString}</Service>"];
	}

	private function getSoapClient()
	{
		if ( !self::$client ) {
			$soapApiUrl = 'http://162.16.1.137:43294/icop/services/JTService?wsdl';
			self::$client = new SoapClient( $soapApiUrl );
		}
		return self::$client;
	}

	private function constructHeader( $ServiceCode, $bodyXmlString, $RequestType='0', $Encrypt='0' )
	{
		$header = [];
		$header['ProductId'] = '';
		$header['ServiceCode'] = $ServiceCode; // 服务编码
		$header['ChannelId'] = '607';	// 渠道号
		$header['ExternalReference'] = $this->getExternalReference(); // 渠道流水号
		$header['OriginalChannelId'] = '002'; // 原渠道号  -- 目前照例子填的
		$header['OriginalReference'] = '201408210006485'; // 原渠道流水号 -- 目前也是乱填
		$header['RequestTime'] = date('YmdHis'); // 请求时间
		$header['TradeDate'] = substr( $header['RequestTime'], 0, 8 ); // 交易日期
		$header['Version'] = '1.0'; // 报文头版本  -- 照着例子写的
		$header['RequestBranchCode'] = 'CN0010001'; // 请求机构代号 -- 照例
		$header['RequestOperatorId'] = 'FB.ICOP.X01'; // 请求柜员代号
		$header['RequestOperatorType'] = '0'; // 请求柜员类型 0-实柜员 1-虚柜员
		//$header['BankNoteBoxID'] = $a; // 柜员或是机具的钱箱号
		//$header['AuthorizerID'] = $a; // 授权柜员号
		$header['TermType'] = '00000'; // 终端类型
		$header['TermNo'] = '0000000000'; // 终端号
		$header['RequestType'] = $RequestType; // 请求类型 0：正常 1：测试 2：重发
		$header['Encrypt'] = $Encrypt; // 加密标志 0:明文 1:密文
		$header['SignData'] = $this->CreateSignData( $bodyXmlString ); // 签名数据
		return  $this->arrayToXml( ['Header'=>$header] );
	}

	private  function constructBody( $requestParms )
	{
		$data = [
			'Body' => [
				'Request' => $requestParms
			],
		];
		return $this->arrayToXml( $data );
	}

	private function CreateSignData( $body )
	{
		return ''; // 数字签名加密
	}

	/**
	 * @brief:  获取流水号，渠道流水号
	 * @return:  
	 */
	protected function getExternalReference()
	{
		$d = explode( '.', microtime(true) );
		return date( 'YmdHis' ) . str_pad( $d[1], 4, 0, STR_PAD_LEFT) . str_pad( mt_rand(1, 999), 3, 0, STR_PAD_LEFT);
	}


	/**
	 * @brief:  XML 返回结果 转数组
	 * @param:  $xml
	 * @return:  
	 */
	private function xmlToArray( $xml )
	{
		return json_decode( json_encode( simplexml_load_string( $xml ) ), true );
	}

	
	/**
	 * @brief:  参数数组装成XML
	 * @param:  $arr
	 * @return:  
	 */
	private function arrayToXml( $arr )
	{
		$xml = '';
		foreach ( $arr as $k=>$v )
		{
			if ( $v === '' ) 
			{
				$xml .= "<{$k}/>";
			}
			else
			{
				$xml .= "<{$k}>";
				if ( is_array( $v ) ) {
					$xml .= $this-> arrayToXml( $v );
				} else {
					$xml .= "{$v}";
				}
				$xml .= "</{$k}>";
			}
		}
		return $xml;
	}

	public function test_self_send(){
	    $model = $this->model('test');
	    
	    $params = array('account'=>'9559988067872');
	    Log::notice("postRequest data ===========test_self_send================>> data = ##" . json_encode($params) . "##" );
	    
	    $data = $model->test_self($params);
        Log::notice("response-data =============test_self_send==============>> data = ##" . json_encode($data) . "##" );
	    
        EC::success(EC_OK);
	}
	
	public function test_self2(){
	    Log::notice("##\r\n\r\n##");
	    $post_data = getPostStr();
// 	    Log::notice("postRequest data ============test_self======11=========>> data-post_data = ##" . $post_data . "## is_string=" . is_string($post_data) );
// 	    Log::notice("postRequest data ============test_self======22=========>> data-post_data = ##" . json_encode($post_data) . "## is_string=" . is_string(json_encode($post_data)) );
// 	    Log::notice("postRequest data ============test_self======33=========>> data-post_data = ##" . json_encode(json_decode($post_data)) . "## is_string=" . is_string(json_encode(json_decode($post_data))) );
	    
// 	    $post_data = json_decode($post_data);
// 	    Log::notice("postRequest data ============test_self======44=========>> data-post_data = ##" . $post_data . "##" );
	    
// 	    Log::notice("post_data-str=" . str_replace('\"','\'',strval($post_data)));
// 	    $post_data = json_encode( str_replace('\"','\'',strval($post_data)) );
	    
// 	    Log::notice("--------->>>post_data=" . json_decode($post_data,true) );

	    $post_data = json_decode($post_data,true);
	    Log::notice("postRequest data ===========================>> data = ##" . json_encode($post_data) . "##" );
	    
	    $account = $post_data['data']['account'];
// 	    $account = Request::post('account');
	    Log::notice("postRequest data ===========test_self=======555=========>> data-account = ##" . $account . "##" );
	    
	    $model = $this->model('test');
	    
	    EC::success(EC_OK);
	}
	
	public function test_self(){
	    $post_data = getPostStr();
	    if(empty($post_data)){
	        $post_data = array();
	    } else {
	       $post_data = json_decode($post_data,true);
	    }
	    $account = $post_data['data']['account'];
	    Log::notice("postRequest data ===========test_self================>> data-account = ##" . $account . "##" );
	    EC::success(EC_OK);
	}
	
}
