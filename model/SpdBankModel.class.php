<?php
class SpdBankModel extends SPDBankCurl
{
    
    public function curlSpdRequestXml($params = '',$transCode){
        $conf_arr = Controller::getConfig('conf');
        $masterID = $conf_arr['ddmg_spd_masterID'];
        $packetID = date('YmdHis',time()) . strval(rand(1000,9999));
        return self::curlSpdRequest($params, $transCode, $masterID, $packetID);
    }
    
    
    private  function constructBody( $params ) {
        Log::notice('str-constructBody ==== >>> params=' . json_encode($params) );
        $data = [ 'body' => $params ];
        return $this->arrayToXml( $data );
    }
    
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
    
    /**
     * 5144虚账户母子关系登记薄查询
     * @param unknown $params
     */
    public function queryChildAccount($params = array()){
        Log::notice('str-queryChildAccount ==== >>> params=' . json_encode($params) );
        
        $transCode = "5144";
        $requestParms = [];
        
        $mustFields = ['beginNumber', 'queryNumber'];
        foreach ( $mustFields as $v )
        {
            if ( '0'!==strval($params[$v]) && !$params[$v] ) {
                Log::error('params['.$v.'] is emtpy.');
                return false;
            }
            $requestParms[$v] = $params[$v];
        }
        
        $conf_arr = Controller::getConfig('conf');
        $requestParms['acctNo'] = $conf_arr['ddmg_spd_acctNo'];
        
        $requestParms['virtualAcctNo'] = $params['virtualAcctNo'];
        
        $bodyXmlStr = $this->constructBody($requestParms);
        
        Log::notice('end-queryChildAccount ==== >>> bodyXmlStr=##' . strval($bodyXmlStr) . '##');
        return $this->curlSpdRequestXml($bodyXmlStr,$transCode);
    }
    
    /**
     * 5145虚账户已分摊交易明细查询
     * @param unknown $params
     */
    public function queryAccountTransferAmount($params = array()){
        Log::notice('str-queryAccountTransferAmount ==== >>> params=' . json_encode($params) );
    
        $transCode = "5145";
        $requestParms = [];
    
        // 起始笔数 查询笔数    虚账号 分摊开始日期  分摊结束日期
        // 分摊结束日期不能在分摊开始日期之前，日期间隔不能超过一个月
        $mustFields = ['beginNumber', 'queryNumber', 'virtualAcctNo', 'shareBeginDate', 'shareEndDate'];
        foreach ( $mustFields as $v )
        {
            if ( '0'!==strval($params[$v]) && !$params[$v] ) {
                Log::error('params['.$v.'] is emtpy.');
                return false;
            }
            $requestParms[$v] = $params[$v];
        }
        
        $requestParms['jnlSeqNo'] = $params['jnlSeqNo']; // 业务流水号 交易流水号
        $requestParms['summonsNumber'] = $params['summonsNumber']; // 流水号的组内序号
        // 交易结束日期不能在交易开始日期之前，时间间隔不能超过一个月。
        $requestParms['transBeginDate'] = $params['transBeginDate']; // 交易开始日期 交易流水产生时间
        $requestParms['transEndDate'] = $params['transEndDate']; // 交易结束日期 交易流水结束时间
        
        $conf_arr = Controller::getConfig('conf');
        $requestParms['acctNo'] = $conf_arr['ddmg_spd_acctNo']; // 实账号 母实子虚的母账号，银企直连签约账号
        
        $bodyXmlStr = $this->constructBody($requestParms);
    
        Log::notice('end-queryAccountTransferAmount ==== >>> bodyXmlStr=##' . strval($bodyXmlStr) . '##');
        return $this->curlSpdRequestXml($bodyXmlStr,$transCode);
    }
    
    /**
     * EG48网银支付行名行号表查询
     * @param unknown $params
     * @return string
     */
    public function queryBankNumberByName($params = array()){
        Log::notice('str-queryBankNumberByName ==== >>> params=' . json_encode($params) );
        
        $transCode = "EG48";
        $requestParms = [];
        
        $bankName = $this->iconvFunc($params['bankName']);
        Log::notice('str-queryBankNumberByName ==== >>> params-bankName=' . $bankName );
        
        $requestParms['bankName'] = $bankName;
        
        $bodyXmlStr = $this->constructBody($requestParms);
        
        Log::notice('end-queryBankNumberByName ==== >>> bodyXmlStr=##' . strval($bodyXmlStr) . '##');
        return $this->curlSpdRequestXml($bodyXmlStr,$transCode);
    }
    
    /**
     * 5148虚账户发起转账
     * @param unknown $params
     */
    public function sendTransferTrade($params = array()){
        Log::notice('str-sendTransferTrade ==== >>> params=' . json_encode($params) );
    
        $transCode = "5148";
        $requestParms = [];
    
        $requestParms['electronNumber'] = "";//$params['electronNumber']; // 电子凭证号
        $requestParms['appointDate'] = "";//$params['appointDate']; // 指定日期 不输入时表示实时，输入日期为当天时也表示实时
        $requestParms['payerVirAcctNo'] = $params['payerVirAcctNo']; // 付款人虚账号
        $requestParms['payerName'] = $this->iconvFunc($params['payerName']); // 付款人名称
        $requestParms['payeeAcctType'] = "0";//$params['payeeAcctType']; // 收款人账户类型 0-对公账号 1-卡 2-活期一本通 3-定期一本通 4-定期存折 5-存单 6-国债 9-其他账号
        $requestParms['payeeAcctNo'] = $params['payeeAcctNo']; // 收款人账号
        $requestParms['payeeAcctName'] = $this->iconvFunc($params['payeeAcctName']); // 收款人中文名
        $requestParms['payeeBankNo'] = $params['payeeBankNo']; // 支付号 【收款账号行号】
        $requestParms['ownItBankFlag'] = $params['ownItBankFlag']; // 本行/它行标志 0：表示本行 1：表示它行
        $requestParms['payeeBankName'] = $this->iconvFunc($params['payeeBankName']); // 收款行名称 跨行转账时必须输入(即本行/它行标志为1：表示它行)
        $requestParms['payeeBankAddress'] = $this->iconvFunc($params['payeeBankAddress']); // 收款行地址 跨行转账时必须输入(即本行/它行标志为1：表示它行)
        $requestParms['remitLocation'] = $params['remitLocation']; // 同城异地标志 0：同城 1：异地 跨行转账时必须输入(即本行/它行标志为1：表示它行)
        $requestParms['transAmount'] = $params['transAmount']; // 交易金额
        $requestParms['note'] = $this->iconvFunc($params['note']); // 附言 如果跨行转账，附言请不要超过42字节（汉字21个）
        $requestParms['payeeBankSelectFlag'] = "1";//$params['payeeBankSelectFlag']; // 收款行速选标志 新增字段（可不用） 1-速选 当同城异地标志为“异地”时才能生效
    
        $conf_arr = Controller::getConfig('conf');
        $requestParms['acctNo'] = $conf_arr['ddmg_spd_acctNo']; // 实账号 母实子虚的母账号，银企直连签约账号
        
        $bodyXmlStr = $this->constructBody($requestParms);
    
        Log::notice('end-sendTransferTrade ==== >>> bodyXmlStr=##' . strval($bodyXmlStr) . '##');
        return $this->curlSpdRequestXml($bodyXmlStr,$transCode);
    }
    
    /**
     * 8924账户明细查询
     * @param unknown $params
     */
    public function queryAccountTrade($params = array()){
        Log::notice('str-queryAccountTrade ==== >>> params=' . json_encode($params) );
    
        $transCode = "8924";
        $requestParms = [];
    
        $requestParms['beginDate'] = $params['beginDate']; // 开始日期
        $requestParms['endDate'] = $params['endDate']; // 结束日期
        $requestParms['queryNumber'] = $params['queryNumber']; // 查询的笔数
        $requestParms['beginNumber'] = $params['beginNumber']; // 查询的起始笔数
        
        $requestParms['transAmount'] = $params['transAmount']; // 交易金额
        $requestParms['subAccount'] = $params['subAccount']; // 对方帐号
        $requestParms['subAcctName'] = $params['subAcctName']; // 对方户名
    
        $conf_arr = Controller::getConfig('conf');
        $requestParms['acctNo'] = $conf_arr['ddmg_spd_acctNo']; // 实账号 母实子虚的母账号，银企直连签约账号
    
        $bodyXmlStr = $this->constructBody($requestParms);
    
        Log::notice('end-queryAccountTrade ==== >>> bodyXmlStr=##' . strval($bodyXmlStr) . '##');
        return $this->curlSpdRequestXml($bodyXmlStr,$transCode);
    }
    
}