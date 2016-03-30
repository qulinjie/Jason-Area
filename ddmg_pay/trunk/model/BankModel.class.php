<?php

class BankModel extends CSBankSoap
{

/********************************  客户/帐号类接口  ************************************/
	/**
	 * @brief:  客户注册通知
	 * @return:  
	 *		商户把客户资料传输到银行，银行进行虚拟账户开户注册。
	 *		银行返回成功表示银行已接收商户传输过来的客户资料，并不是表示客户已与银行签约。
	 */
	public function registerCustomer( $registerData )
	{
		$ServiceCode = 'FMSCUST0001'; // 客户注册通知 

		Log::notice("postRequest data ===========================>> data-registerData = ##" . json_encode($registerData) . "##" );
		
		if ( !$registerData || !is_array( $registerData ) ) {
		    Log::error("param registerData is illegal .");
			return false;
		}

		$requestParms = [];

		// 必填字段
		$mustFields = ['MCH_NO', 'CUST_CERT_TYPE', 'CUST_CERT_NO', 'SIT_NO', 'CUST_NAME', 'CUST_ACCT_NAME', 'CUST_SPE_ACCT_NO', 'CUST_SPE_ACCT_BKTYPE', 'ENABLE_ECDS', 'IS_PERSON'];
		foreach ( $mustFields as $v )
		{
			if ( '0'!==strval($registerData[$v]) && !$registerData[$v] ) {
				Log::error(' no have $registerData['.$v.'] ');
				return false;
			}
			$requestParms[$v] = $registerData[$v];
		}

		// CUST_SPE_ACCT_BKTYPE为1时必填字段
		Log::notice("postRequest data ===========================>> data-CUST_SPE_ACCT_BKTYPE = ##" . ( '1'===strval($registerData['CUST_SPE_ACCT_BKTYPE']) ) . "##" );
		Log::notice("postRequest data ===========================>> data-CUST_SPE_ACCT_BKTYPE = ##" . ( '1'==strval($registerData['CUST_SPE_ACCT_BKTYPE']) ) . "##" );
		if ( '1'==strval($registerData['CUST_SPE_ACCT_BKTYPE']) ) {
			$shouldCheck = ['CUST_SPE_ACCT_BKID', 'CUST_SPE_ACCT_BKNAME'];
			foreach ( $shouldCheck as $k ) {
				if ( !$registerData[$k] ) { // 应该加验证
					Log::error(' no have $registerData['.$v.'] ');
					return false;
				}
				$requestParms[$v] = $registerData[$v];
			}
		}
		
		$requestParms['CUST_SPE_ACCT_BKID'] = $registerData['CUST_SPE_ACCT_BKID'];
		$requestParms['CUST_SPE_ACCT_BKNAME'] = $registerData['CUST_SPE_ACCT_BKNAME'];
		
		/*
		$requestParms['MCH_NO'] = '';					// 商户编号
		$requestParms['CUST_CERT_TYPE'] = '';			// 客户证件类型
		$requestParms['CUST_CERT_NO'] = '';				// 客户证件号码
		$requestParms['SIT_NO'] = '';					// 席位号
		$requestParms['CUST_NAME'] = '';				// 客户名称
		$requestParms['CUST_ACCT_NAME'] = '';			// 客户账户名
		$requestParms['CUST_SPE_ACCT_NO'] = '';			// 客户结算账户
		$requestParms['CUST_SPE_ACCT_BKTYPE'] = '';	// 客户结算账户行别
			$requestParms['CUST_SPE_ACCT_BKID'] = '';	// 客户结算账户行号
			$requestParms['CUST_SPE_ACCT_BKNAME'] = '';	// 客户结算账户行名
	$requestParms['CUST_PHONE_NUM'] = '';			// 客户手机号码
	$requestParms['CUST_TELE_NUM'] = '';			// 客户电话号码
	$requestParms['CUST_ADDR'] = '';				// 客户地址
	$requestParms['RMRK'] = '';						// 备注
		$requestParms['ENABLE_ECDS'] = '';				// 是否开通电票
		$requestParms['IS_PERSON'] = '';				// 是否个人
		 */

		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
	}

	/**
	 * @brief:  查询市场的基本信息, 商户编号
	 * @brief:  根据商户编号，查询市场的基本信息？
	 * @return:  
	 */
	public function getMarketBasicInfo( $MCH_NO )
	{
		$ServiceCode = 'FMSCUST0002'; // 查询市场的基本信息
		if ( !$MCH_NO ) {
		    LOG::notice("getCustomerInfo . MCH_NO=" . $MCH_NO );
			return false;
		}
		$requestParms = ['MCH_NO'=> $MCH_NO ];
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=true );
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
	}

	/**
	 * @brief:  查询客户信息
	 * @param:  $MCH_NO
	 * @param:  $SIT_NO
	 * @return:  
	 */
	public function getCustomerInfo( $MCH_NO, $SIT_NO )
	{
		$ServiceCode = 'FMSCUST0003'; // 查询市场的基本信息
		if ( !$MCH_NO || !$SIT_NO ) {
		    LOG::notice("getCustomerInfo . MCH_NO=" . $MCH_NO . ',SIT_NO=' . $SIT_NO);
			return false;
		}
		$requestParms = [ 'MCH_NO'=> $MCH_NO, 'SIT_NO'=>$SIT_NO ];
		LOG::notice("getCustomerInfo . requestParms=" . json_encode($requestParms) );
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
// 		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=true );
	}


	/**
	 * @brief:  查询市场的详细信息 （查询市场子账号信息）
	 * @param:  $MCH_NO
	 * @return:  
	 */
	public function getMarketInfo( $MCH_NO )
	{
		$ServiceCode = 'FMSCUST0007'; // 查询市场的详细信息
		if ( !$MCH_NO ) {
		    LOG::notice("getCustomerInfo . MCH_NO=" . $MCH_NO );
			return false;
		}
		$requestParms = ['MCH_NO'=> $MCH_NO ];
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=true );
	}

/********************************  出金入金交易  ************************************/

	/**
	 * @brief:  客户出金
	 * @param:  $MCH_NO		商户编号
	 * @param:  $SIT_NO		席位号
	 * @param:  $MCH_TRANS_NO	商户交易流水号
	 * @param:  $CURR_COD	币别
	 * @param:  $TRANS_AMT	交易金额
	 * @param:  $TRANS_FEE	手续费
	 * @return:  
	 */
	public function customerOutflow( $MCH_NO, $SIT_NO, $MCH_TRANS_NO, $CURR_COD, $TRANS_AMT, $TRANS_FEE )
	{
		$ServiceCode = 'FMSPAY0002'; // 查询市场的详细信息
		if ( !$MCH_NO || !$SIT_NO || !$MCH_TRANS_NO || !$CURR_COD || !$TRANS_AMT || 0 == strlen(strval($TRANS_FEE))) {
			return false;
		}
		$requestParms = ['MCH_NO'=> $MCH_NO, 'SIT_NO'=>$SIT_NO, 'MCH_TRANS_NO'=>$MCH_TRANS_NO, 'CURR_COD' => $CURR_COD, 'TRANS_AMT'=>$TRANS_AMT, 'TRANS_FEE'=>$TRANS_FEE ];
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=true );
	}

	/**
	 * @brief:  客户入金
	 * @param:  $MCH_NO		商户编号
	 * @param:  $SIT_NO		席位号
	 * @param:  $MCH_TRANS_NO	商户交易流水号
	 * @param:  $CURR_COD	币别
	 * @param:  $TRANS_AMT	交易金额
	 * @return:  
	 */
	public function customerInflow( $MCH_NO, $SIT_NO, $MCH_TRANS_NO, $CURR_COD, $TRANS_AMT )
	{
		$ServiceCode = 'FMSPAY0012'; // 查询市场的详细信息
		if ( !$MCH_NO || !$SIT_NO || !$MCH_TRANS_NO || !$CURR_COD || 0 == strlen(strval($TRANS_AMT)) ) {
			return false;
		}
		$requestParms = ['MCH_NO'=> $MCH_NO, 'SIT_NO'=>$SIT_NO, 'MCH_TRANS_NO'=>$MCH_TRANS_NO, 'CURR_COD' => $CURR_COD, 'TRANS_AMT'=>$TRANS_AMT ];
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=true );
	}


	/**
	 * @brief:  无冻结现货交易付款
	 * @return:  
	 */
	public function notFrozenSpotsTradePay( $params )
	{
		$ServiceCode = 'FMSTRAN0011'; 

		if ( !$params || !is_array( $params ) ) {
			return false;
		}

		$requestParms = [];

		// 必填字段
		$mustFields = ['MCH_NO', 'CTRT_NO', 'BUYER_SIT_NO', 'SELLER_SIT_NO', 'FUNC_CODE', 'TX_AMT', 'SVC_AMT', 'BVC_AMT', 'CURR_COD', 'MCH_TRANS_NO', 'ORGNO', 'TICKET_NUM'];
		foreach ( $mustFields as $v )
		{
			if ( '0'!=strval($params[$v]) && !$params[$v] ) {
				Log::error(' no have $params['.$v.'] ');
				return false;
			}
			$requestParms[$v] = $params[$v];
		}

		// CUST_SPE_ACCT_BKTYPE为1时必填字段
		/*
		if ( '1'===strval($registerData['CUST_SPE_ACCT_BKTYPE']) ) {
			$shouldCheck = ['CUST_SPE_ACCT_BKID', 'CUST_SPE_ACCT_BKNAME'];
			foreach ( $shouldCheck as $k ) {
				if ( !$registerData[$k] ) { // 应该加验证
					Log::error(' no have $registerData['.$v.'] ');
					return false;
				}
				$requestParms[$v] = $registerData[$v];
			}
		}
		 */
		
		/*
		$requestParms['MCH_NO'] = '';					// 商户编号
		$requestParms['CUST_CERT_TYPE'] = '';			// 客户证件类型
		$requestParms['CUST_CERT_NO'] = '';				// 客户证件号码
		$requestParms['SIT_NO'] = '';					// 席位号
		$requestParms['CUST_NAME'] = '';				// 客户名称
		$requestParms['CUST_ACCT_NAME'] = '';			// 客户账户名
		$requestParms['CUST_SPE_ACCT_NO'] = '';			// 客户结算账户
		$requestParms['CUST_SPE_ACCT_BKTYPE'] = '';	// 客户结算账户行别
			$requestParms['CUST_SPE_ACCT_BKID'] = '';	// 客户结算账户行号
			$requestParms['CUST_SPE_ACCT_BKNAME'] = '';	// 客户结算账户行名
	$requestParms['CUST_PHONE_NUM'] = '';			// 客户手机号码
	$requestParms['CUST_TELE_NUM'] = '';			// 客户电话号码
	$requestParms['CUST_ADDR'] = '';				// 客户地址
	$requestParms['RMRK'] = '';						// 备注
		$requestParms['ENABLE_ECDS'] = '';				// 是否开通电票
		$requestParms['IS_PERSON'] = '';				// 是否个人
		 */

		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
	}


	/**
	 * @brief:  客户出入金交易明细查询
	 * @param:  $params
	 * @return:  
	 */
	public function customerInflowQuery( $params )
	{
		$ServiceCode = 'FMSPAY0003';
		if ( !$params || !is_array( $params )) {
			Log::bcsError('params type or value error');
			return false;
		}

		$requestParms = [];
		//必填字段
		foreach(['MCH_NO','PAGE_NUMBER','PAGE_SIZE'] as $v){
			if (!isset($params[$v]) || !$requestParms[$v] = $params[$v]) {
				Log::bcsError('customerInflowQuery params required field miss');
				return false;
			}
		}

		// 非必填字段
		foreach ( ['START_DATE', 'END_DATE', 'FMS_TRANS_NO', 'MCH_TRANS_NO','SIT_NO'] as $v ){
			isset($params[$v]) && $params[$v] && $requestParms[$v] = $params[$v];
		}

		return $this-> sendQuery( $ServiceCode, $requestParms, $fetchAll=false );
	}

	/**
	 * 客户收付款明细查询
	 * @param $FUNC_CODE
	 * @param $params
	 * @return array|bool
	 */
	public function customerIncomePayQuery($FUNC_CODE,$params)
	{
		$requestParms  = [];
		$ServiceCode = 'FMSTRAN0009';
		switch($FUNC_CODE){
			case 1:
				$requiredFiled = ['MCH_NO','SIT_NO','PAGE_NUMBER','PAGE_SIZE'];
				break;
			case 2:
				$requiredFiled = ['MCH_NO','MBR_CERT_TYPE','MBR_CERT_NO','PAGE_NUMBER','PAGE_SIZE'];
				break;
			case 3:
				$requiredFiled = ['MBR_SERVICE_NO','PAGE_NUMBER','PAGE_SIZE'];
				break;
			default:
				Log::bcsError('FUNC_CODE error');
				return false;
		}
		$requestParms['FUNC_CODE'] = $FUNC_CODE;
		//必填字段
		foreach($requiredFiled as $v){
			if(!isset($params[$v]) || !$requestParms[$v] = $params[$v]){
				Log::bcsError('customerIncomePayQuery params required field miss');
				return false;
			}
		}
		//非必填字段
		foreach(['CTRT_NO','START_DATE','END_DATE','PAGE_NUMBER','PAGE_SIZE'] as $v){
			isset($params[$v]) && $params[$v] && $requestParms[$v] = $params[$v];
		}

		return $this->sendQuery($ServiceCode, $requestParms, $fetchAll=false);
	}

	/**
	 * 交易状态查询
	 * @param $OLD_TRANS_NO
	 * @param $FUNC_CODE
	 * @return array|bool
	 */
	public function transactionStatusQuery($OLD_TRANS_NO,$FUNC_CODE = '0')
	{
		$ServiceCode = 'FMSTRAN0010';
		return $this->sendQuery($ServiceCode, ['OLD_TRANS_NO' => $OLD_TRANS_NO,'FUNC_CODE' => $FUNC_CODE], $fetchAll=false);
	}

/********************************  现货交易  ************************************/

/********************************  日终批量交易  ************************************/

/********************************  电子票务  ************************************/



}
