<?php
class SpdBankModel extends SPDBankCurl
{
    
    public function curlSpdRequestXml($params = '',$transCode){
        $conf_arr = Controller::getConfig('conf');
        $masterID = $conf_arr['ddmg_spd_masterID'];
        $packetID = date('YmdHis',time());
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
    
    public function iconvFunc($param = ''){
        $result = iconv('UTF-8', 'GB2312', $param);
        if( empty($result) ) {
            $result = iconv('UTF-8', 'GBK', $param);
        }
        return $result;
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
        
        Log::notice('end-queryAccountTransferAmount ==== >>> bodyXmlStr=##' . strval($bodyXmlStr) . '##');
        return $this->curlSpdRequestXml($bodyXmlStr,$transCode);
    }
    
    
}