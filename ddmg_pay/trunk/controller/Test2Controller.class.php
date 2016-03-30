<?php
class Test2Controller extends BaseController
{
	protected static $client;

    public function handle( $params= [] )
    {
		if ( !$params ) {
		    // 
		} else 
        switch( $params[0] )
        {
            
            case 'getMarketInfo':
                $this-> getMarketInfo();
                break;
            case 'getMarketBasicInfo':
                $this-> getMarketBasicInfo();
                break;
            case 'registerCustomer':
                $this-> registerCustomer();
                break;
                
            default:
                Log::error('page not found');
                EC::page_not_found();
                break;
        }
    }

    // FMSCUST0007_市场账号信息查询
    public function getMarketInfo(){
        $bcsBank_model = $this->model('bank');
        $conf = $this->getConfig('conf');
    
        $mch_no = $conf['MCH_NO'];
    
        $bcs_data = $bcsBank_model->getMarketBasicInfo( $mch_no );
        Log::notice('loadInfo ==== >>> getMarketInfo response=##' . json_encode($bcs_data) . '##');
    
        EC::success(EC_OK, $bcs_data);
    }
    
    // FMSCUST0002_市场基本信息查询
    public function getMarketBasicInfo(){
        $bcsBank_model = $this->model('bank');
        $conf = $this->getConfig('conf');
        
        $mch_no = $conf['MCH_NO'];
        
        $bcs_data = $bcsBank_model->getMarketBasicInfo( $mch_no );
        Log::notice('loadInfo ==== >>> getMarketBasicInfo response=##' . json_encode($bcs_data) . '##');
        
        EC::success(EC_OK, $bcs_data);
    }
    
    // FMSCUST0001_客户注册通知
    public function registerCustomer(){
        $params = [
            'SIT_NO'               => 'DDMG1111',   // 席位号
            'CUST_CERT_TYPE'       => '02',          // 客户证件类型
            'CUST_CERT_NO'         => '430105660915251',            // 客户证件号码
            'CUST_NAME'            => '陈测试进',          // 客户名称
            'CUST_ACCT_NAME'       => '陈测试进',      // 客户账户名
            'CUST_SPE_ACCT_NO'     => '6223687310880026234',     // 客户结算账户
            'CUST_SPE_ACCT_BKTYPE' => '0',    // 客户结算账户行别 0-长沙银行；1-非长沙银行
            'CUST_SPE_ACCT_BKID'   => '',	// 客户结算账户行号
            'CUST_SPE_ACCT_BKNAME' => '',	// 客户结算账户行名
            'ENABLE_ECDS'          => '',        // 是否开通电票
            'IS_PERSON'            => '1',          // 是否个人 必填0-否，1-是
            'CUST_PHONE_NUM'       => '13265431549',      // 客户手机号码
            'CUST_TELE_NUM'        => '13265431549',       // 客户电话号码
            'CUST_ADDR'            => '',       // 客户地址
            'RMRK'                 => '',           // 客户备注
        ];
        
        $params['MCH_NO']  = $this->getMCH_NO(); // 商户编号
        
        $bank_model = $this->model('bank');
        $conf = $this->getConfig('conf');
        $bcsRegister_model = $this->model('bcsRegister');
        
        $data = $bank_model->registerCustomer($params);
         
        $CSBankSoapUrl = $conf['CSBankSoapUrl'];
        $params = array();
        $params['wsdlUrl'] = strval($CSBankSoapUrl);
        $params['xml'] = strval($data[0]);
        Log::notice('params==createByJava--------------------->>' . var_export($params, true));
        $data = $bcsRegister_model->createByJava($params);
        
        EC::success(EC_OK, $data);
    }
    
    
    
}
