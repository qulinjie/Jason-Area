<?php

class OrderController extends BaseController
{
	
	public function handle( $params=[] )
	{
		if ( !$params ) {
			// 无参数，默认订单列表
			$this-> orderList();
		}
	   	else switch( $params[0] )
		{
			case 'cancel': //  取消订单
				$this-> cancel();
				break;

			case 'done': // 完成订单
				$this-> done();
				break;

			case 'add': //  帮客户添加订单
				$this-> add();
				break;

			case 'doAdd': // 执行添加
				$this-> doAdd();
				break;
			case 'getUser':
				$this-> ajaxGetUserInfoById();
				break;

			case 'getBuyer':
				$this-> ajaxGetBuyerByAccount();
				break;

			case 'unlinePay':
				$this-> completeUnlinePayInfo(); // AJAX版本，做错了
				break;

			case 'unlinePayFor':
				$this-> completeUnlinePayFor(); // 
				break;

			case 'delivery':
				$this-> delivery(); //  交付信息
				break;
			case 'deliveryInfo':
				$this-> deliveryInfo(); //  交付信息
				break;


			case 'selectItemList': // 执行添加
				$this-> getSelectItemList();
				break;
			case 'list': // 订单列表
				$this-> orderList();
				break;
			case 'edit': // 修改订单
				$this-> edit();
				break;
			case 'doEdit': // 执行修改
				$this-> doEdit();
				break;

			case 'audit': //  审核
				$this-> audit();
				break;

			case 'doAudit': //  执行审核
				$this-> doAudit();
				break;

			case 'detail': //  详情
				$this-> detail();
				break;

			case 'delete': // 删除订单
				$this-> delete();
				break;
			case 'invoicing': //修改发票状态
				$this->doInvoicing();
				break;
			default:
				Log::error('page not found');
				EC::page_not_found();
				break;
		}
	}


	/**
	 * @brief:   添加订单--卖家帮买家下单
	 * @return:  
	 */
	private function add()
	{
		$statusConfig = Controller::getConfig( 'status' ); // 获取配置
		$data = [];
		$data['queryString'] = $this->getQueryString('id');
		$data['shipping_type_list'] = $statusConfig['order']['shipping_type'];
		$data['city_list'] = $this->model( 'city' )->findAll();
		$data['factory_list'] = $this->model( 'factory' )->findAll();
		$orderAdd_html = $this->render('orderAdd', $data, true);
		$this->render( 'index', array( 'page_type'=>'orderAdd', 'orderAdd_html'=>$orderAdd_html ) );
	}


	private function ajaxGetBuyerByAccount()
	{
		$buyer_account = $this->post( 'buyer_account', 0 );
		if ( !$buyer_account ) {
			$this-> jsonFail( '请输入买家帐号' );
		}
		$getUserAccountRes = $this->model( 'user' )->getUserByAccount( $buyer_account );
		if ( $getUserAccountRes['code']!==0 && !$getUserAccountRes['data'] ) {
			$this-> jsonFail( '买家帐号无效' );
		}
		if ( $getUserAccountRes['data']['is_buyer'] != 1 ) {
			$this-> jsonFail( '买家帐号无效' );
		}


		$this-> jsonSuccess( '买家帐号有效', $getUserAccountRes['data'] );
	}

	private function ajaxGetUserInfoById()
	{
		$user_id = $this->post( 'user_id', 0 );
		if ( !$user_id ) {
			$this-> jsonFail( '用户ID' );
		}
		$user_info = $this->model( 'user' )->getUserBasicInfo( $user_id );
		if ( !$user_info ) {
			$this-> jsonFail( '没用户信息' );
		}

		$this-> jsonSuccess( '获取成功', $user_info );
	}


	/**
	 * @brief:  执行添加
	 * @return:  
	 */
	private function doAdd()
	{
		$loginUser = $this->getLoginSeller();
		if ( $loginUser['is_partner'] != 1 ) {
			$this-> jsonFail( ' not partner ' ); // 不是合伙人
		}

		$newOrder = [];
		$newOrder['buyer_account'] = $this->post( 'buyer_account' );
		if ( !$newOrder['buyer_account'] ) {
			$this-> jsonFail( 'no have buyer account' );
		}
		$newOrder['shipping_type'] = $this->post( 'shipping_type', 0 );
		if ( !$newOrder['shipping_type'] ) {
			$this-> jsonFail( 'no have shipping_type' );
		}
		$newOrder['consignee'] = $this->post( 'consignee' );
		if ( !$newOrder['consignee'] ) {
			$this-> jsonFail( 'no have consignee' );
		}
		$newOrder['tel'] = $this->post( 'tel', 0 );
		if ( !$newOrder['tel'] ) {
			$this-> jsonFail( ' no have tel ' );
		}
		$newOrder['address'] = $this->post( 'address' );
		if ( $neworder['shipping_type']==2 && !$newOrder['address'] ) {
			$this-> jsonFail( ' no have address ' );
		}

		// 订单商品
		$newOrder['items'] = $_POST['items'];
		if ( !$newOrder['items'] ) {
			$this-> jsonFail( ' no have order items ' );
		}

		$addNewOrderRes = $this->model( 'order' )->addNewOrder( $newOrder );
		if ( $addNewOrderRes['code']!==0 ) {
				$this-> jsonFail( ' add fail ' );
		}
		$this-> jsonSuccess( 'add new order success' );
		//$this-> jsonSuccess( '修改成功' );
	}


	/**
	 * @brief:  获取商品选择列表
	 * @return:  
	 */
	private function getSelectItemList(  )
	{
		// 获取查询条件
		$condition = $this->getSelectItemConditionArr();
		$itemModel = $this->model( 'item' );

		// 总条数
		//$total = $itemModel->count( null, '1', $condition['whereK'], $condition['whereV'] );
		$getCountRes = $itemModel->getCount( $condition['condition'] );
		$total = $getCountRes['data'];

		// 分页
		$pager_html = $this->getPageHtml( $condition['condition']['page'], $total, $condition['condition']['count'] );

		// 获取数据
		$getListRes = $itemModel->getList( $condition['condition'] );
		$items = $getListRes['data'];
		
		$data = [];
		$data['queryString'] = $this->getQueryString();
		$data['params'] = $condition['params'];
		$data['items'] = $items;
		$data['city_list'] = $this->model( 'city' )->findAll();
		$data['factory_list'] = $this->model( 'factory' )->findAll();
		//$data['pager_html'] = $pager_html;
		$data['page'] = $condition['condition']['page'];
		$data['numPerPage'] = $condition['condition']['count'];
		
		$this->render('selectItemList', $data );
	}

	private function getSelectItemConditionArr()
	{
		/*
		$conditionArr['category_id'];
		$conditionArr['material_id'];
		$conditionArr['merchant_id'];
		$conditionArr['product_id'];
		$conditionArr['factory_id'];
		$conditionArr['size_id'];
		$conditionArr['count'];
		$conditionArr['page'];
		 */

		// 获取条件
		$conditionArr = $params = [];

		$category_id = (int)$this->get('category_id', 0);
		if ( $category_id ) {
			$conditionArr['category_id'] = $category_id;
			$params['category_id'] = $category_id;
		}
		
		$product_id  = (int)$this->get('product_id', 0);
		if ( $product_id ) {
			$conditionArr['product_id'] = $product_id;
			$params['product_id'] = $product_id;
		}
		
		$material_id = (int)$this->get('material_id', 0);
		if ( $material_id ) {
			$conditionArr['material_id'] = $material_id;
			$params['material_id'] = $material_id;
		}

		$merchant_id = (int)$this->get('merchant_id', 0);
		if ( $merchant_id ) {
			$conditionArr['merchant_id'] = $merchant_id;
			$params['merchant_id'] = $merchant_id;
		}

		$size_id = (int)$this->get('size_id', 0 );
		if ( $size_id ) {
			$conditionArr['size_id'] = $size_id;
			$params['size_id'] = $size_id;
		}

		$factory_id  = (int)$this->get('factory_id', 0);
		if ( $factory_id ) {
			$conditionArr['factory_id'] = $factory_id;
			$params['factory_id'] = $factory_id;
		}

		$city_id = (int)$this->get('city_id', 0 );
		if ( $city_id ) {
			$conditionArr['city_id'] = $city_id;
			$params['city_id'] = $city_id;
		}

		$count = (int)$this->get('count', 1000 );
		if ( $count ) {
			$conditionArr['count'] = $count;
			$params['count'] = $count;
		}

		$page = (int)$this->get('page', 1 );
		if ( $page ) {
			$conditionArr['page'] = $page;
			$params['page'] = $page;
		}

		$conditionArr['is_on_sale'] = 1;// 只能选在售商品
		$conditionArr['is_delete'] = 0;// 非删除的商品

		return ['condition'=>$conditionArr, 'params'=>$params];
	}


	/**
	 * @brief:  从URL中获取查询条件
	 * @return: array( 'whereK'=>[..], 'whereV'=>[..], 'params'=>[] ) ;
	 */
	private function getConditionArry2()
	{
		// 获取条件
		$whereK = $whereV = $params = [];
		( $whereK[] = 'is_delete=?' ) && ( $whereV[] = '0' );
		( $whereK[] = 'is_on_sale=?' ) && ( $whereV[] = '1' );

		$category_id = (int)$this->get('category_id', 0);
		if ( $category_id ) {
			$whereK[] = 'category_id=?';
			$whereV[] = $category_id;
			$params['category_id'] = $category_id;
		}
		
		$product_id  = (int)$this->get('product_id', 0);
		if ( $product_id ) {
			$whereK[] = 'product_id=?';
			$whereV[] = $product_id;
			$params['product_id'] = $product_id;
		}
		
		$material_id = (int)$this->get('material_id', 0);
		if ( $material_id ) {
			$whereK[] = 'material_id=?';
			$whereV[] = $material_id;
			$params['material_id'] = $material_id;
		}

		$size_id = (int)$this->get('size_id', 0 );
		if ( $size_id ) {
			$whereK[] = 'size_id=?';
			$whereV[] = $size_id;
			$params['size_id'] = $size_id;
		}

		$factory_id  = (int)$this->get('factory_id', 0);
		if ( $factory_id ) {
			$whereK[] = 'factory_id=?';
			$whereV[] = $factory_id;
			$params['factory_id'] = $factory_id;
		}

		$city_id = (int)$this->get('city_id', 0 );
		if ( $city_id ) {
			$whereK[] = 'city_id=?';
			$whereV[] = $city_id;
			$params['city_id'] = $city_id;
		}


		$loginSeller = $this->getLoginSeller();
		if ( $loginSeller['merchant_id'] ) {
			$whereK[] = 'merchant_id=?';
			$whereV[] =  $loginSeller['merchant_id'];
		}
		if ( $loginSeller['id'] ) {
			$whereK[] = 'seller_id=?';
			$whereV[] = $loginSeller['id'];
		}

		//( $merchant_id = 12)	&&	( $whereK[] = 'merchant_id=?' ) && ( $whereV[] = $merchant_id );
		//( $seller_id   = 1)		&&	( $whereK[] = 'seller_id=?'   ) && ( $whereV[] = $seller_id );

		return ['whereK'=>$whereK, 'whereV'=>$whereV, 'params'=>$params];
	}



	/**
	 * @brief:   订单列表
	 * @return:  
	 */
	protected function orderList()
	{
		// 获取查询条件
		$condition = $this->getConditionArr();

		// 总条数
		$orderModel = $this->model( 'order' );
		$getCountRes = $orderModel->getCount( $condition['condition'] );
		$total = $getCountRes['data'];
		// 分页
		$pager_html = $this->getPageHtml( $condition['condition']['page'], $total, $condition['condition']['count'] );

		// 获取数据
		$getOrderListRes = $orderModel->getOrderList( $condition['condition'] );
		$orders = $getOrderListRes['data'];

		// 状态配置文件
		$statusConfig = Controller::getConfig( 'status' );
		
		$data = [];
		$data['queryString'] = $this->getQueryString();
		$data['status_list'] = $statusConfig['order']['status'];
		$data['shipping_type_list'] = $statusConfig['order']['shipping_type'];
		$data['invoicing_status']   = $statusConfig['order']['invoicing_status'];
		$data['params'] = $condition['params'];
		$data['orders'] = $orders;
		$data['page'] = $condition['condition']['page'];
		$data['numPerPage'] = $condition['condition']['count'];
		$data['pager_html'] = $pager_html;

		$orderList_html = $this->render('orderList', $data, true);
		$this->render( 'index', array( 'page_type'=>'orderList', 'orderList_html'=>$orderList_html ) );
	}

	private function getConditionArr()
	{
		// 获取条件
		$conditionArr = $params = [];

		$order_num = $this->get('order_num', 0);
		if ( $order_num ) {
			$conditionArr['order_num'] = $order_num;
			$params['order_num'] = $order_num;
		}
		
		$consignee  = $this->get('consignee');
		if ( $consignee ) {
			$conditionArr['consignee'] = $consignee;
			$params['consignee'] = $consignee;
		}
		
		$tel = $this->get('tel', 0);
		if ( $tel ) {
			$conditionArr['tel'] = $tel;
			$params['tel'] = $tel;
		}

		$shipping_type = (int)$this->get('shipping_type', 0);
		if ( $shipping_type ) {
			$conditionArr['shipping_type'] = $shipping_type;
			$params['shipping_type'] = $shipping_type;
		}

		$status = (int)$this->get('status', 0 );
		if ( $status ) {
			$conditionArr['status'] = $status;
			$params['status'] = $status;
		}

		$invoicing_status = (int)$this->get('invoicing_status', 0 );
		if ( $invoicing_status ) {
			$conditionArr['invoicing_status'] = $invoicing_status;
			$params['invoicing_status'] = $invoicing_status;
		}

		$start_date  = $this->get('start_date', 0);
		if ( $start_date ) {
			$conditionArr['start_date'] = $start_date;
			$params['start_date'] = $start_date;
		}

		$end_date = $this->get('end_date', 0 );
		if ( $end_date ) {
			$conditionArr['end_date'] = $end_date;
			$params['end_date'] = $end_date;
		}

		$count = (int)$this->get('count', 10 );
		if ( $count ) {
			$conditionArr['count'] = $count;
			$params['count'] = $count;
		}

		$page = (int)$this->get('page', 1 );
		if ( $page ) {
			$conditionArr['page'] = $page;
			$params['page'] = $page;
		}

		return ['condition'=>$conditionArr, 'params'=>$params];
	}



	/**
	 * @brief:  从URL中获取查询条件
	 * @return: array( 'whereK'=>[..], 'whereV'=>[..], 'params'=>[] ) ;
	 */
	private function getConditionArry()
	{
		// 获取条件
		$whereK = $whereV = $params = [];
		( $whereK[] = 'is_delete=?' ) && ( $whereV[] = '0' );

		// 搜索订单号  order_num
		$order_num = $this->get('order_num', 0);
		if ( $order_num ) {
			$whereK[] = 'order_num=?';
			$whereV[] = $order_num;
			$params['order_num'] = $order_num;
		}
		// 搜索买家姓名
		$consignee  = $this->get('consignee', '');
		if ( $consignee ) {
			$whereK[] = 'consignee like ? "';
			$whereV[] = '%'.$consignee.'%';
			$params['consignee'] = $consignee;
		}
		// 买家联系方式
		$tel = $this->get('tel', 0);
		if ( $tel ) {
			$whereK[] = 'tel=?';
			$whereV[] = $tel;
			$params['tel'] = $tel;
		}

		// 配送类型
		$shipping_type = (int)$this->get('shipping_type', 0 );
		if ( $shipping_type ) {
			$whereK[] = 'shipping_type=?';
			$whereV[] = $shipping_type;
			$params['shipping_type'] = $shipping_type;
		}
		
		// 订单状态
		$status  = (int)$this->get('status', 0);
		if ( $status ) {
			$whereK[] = 'status=?';
			$whereV[] = $status;
			$params['status'] = $status;
		}
		// 开始时间
		$start_date  = $this->get('start_date', 0);
		if ( $start_date ) {
			$whereK[] = 'start_date>?';
			$whereV[] = $start_date;
			$params['start_date'] = $start_date;
		}
		// 结束时间
		$end_date  = $this->get('end_date', 0);
		if ( $end_date ) {
			$whereK[] = 'end_date<?';
			$whereV[] = $end_date;
			$params['end_date'] = $end_date;
		}
	
		$loginSeller = $this->getLoginSeller();
		/*
		if ( $loginSeller['merchant_id'] ) {
			$whereK[] = 'merchant_id=?';
			$whereV[] =  $loginSeller['merchant_id'];
		}
		 */
		if ( $loginSeller['id'] ) {
			$whereK[] = 'seller_id=?';
			$whereV[] = $loginSeller['id'];
		}

		return ['whereK'=>$whereK, 'whereV'=>$whereV, 'params'=>$params];
	}


	/**
   	 * @brief:  审核订单页
	 * @return:  
	 */
	private function audit()
	{
		$id = $this->get( 'id', 0 );
		if ( !$id ) {
			$this-> fail( '请指定订单ID' );
		}

		$orderModel = $this->model( 'order' );
		$getOrderRes = $orderModel->getOrderDetailById( $id );
		if ( $getOrderRes['code']!==0 ) {
			$this-> fail( 'getOrder fail' );
		}
		$order = $getOrderRes['data'];
		if ( !$order ) {
			$this-> fail( 'order is empty' );
		}
		if ( $order['is_delete'] == 1 ) {
			$this-> fail( 'order has been deleted' );
		}
		$loginSellerTel = $this->getLoginSellerAccount();
		if ( $order['seller_tel'] != $loginSellerTel ) { 
			$this-> fail( 'not order owner' );
		}

		$data = [];
		$data['order'] = $order;
		$data['queryString'] = $this->getQueryString('id');
		$orderAudit_html = $this->render('orderAudit', $data, true);
		$this->render( 'index', array( 'page_type'=>'orderAudit', 'orderAudit_html'=>$orderAudit_html ) );
	}


	/**
   	 * @brief:  执行审核
	 * @return:  
	 */
	private function doAudit()
	{
		$id = $this->post( 'id', 0 );
		if ( !$id ) {
			$this-> jsonFail( '请指定订单ID' );
		}
		$audit_result = $this->post( 'audit_result', '' );
		if ( !in_array( $audit_result, ['allow', 'denial'] ) ) {
			$this-> jsonFail( '参数错误' );
		}
		$why_denial = $this->post( 'why_denial', '' );
		if ( $audit_result == 'denial' && $why_denial=='' ) {
			$this-> jsonFail( '请输入不通过审核的理由' );
		}

		$orderModel = $this->model( 'order' );
		$getOrderRes = $orderModel->getOrderDetailById( $id );
		if ( $getOrderRes['code']!==0 ) {
			//$this-> fail( 'getOrder fail' );
			$this-> jsonFail( 'getOrder fail' );
		}
		$order = $getOrderRes['data'];
		if ( !$order ) {
			//$this-> fail( 'order is empty' );
			$this-> jsonFail( 'order is empty' );
		}
		if ( $order['is_delete'] == 1 ) {
			//$this-> fail( 'order has been deleted' );
			$this-> jsonFail( 'order has been deleted' );
		}
		if ( $order['status'] != 1 ) {  // 只能待审核，才可已审核
			$this-> jsonFail( 'status != 1' );
		}
		$loginSellerTel = $this->getLoginSellerAccount();
		if ( $order['seller_tel'] != $loginSellerTel ) { 
			//$this-> fail( 'not order owner' );
			$this-> jsonFail( 'not order owner' );
		}

		$status = ['allow'=>4, 'denial'=>7]; // 审核通过变为4待支付，否则变为7不通过
		$data['status']				= $status[$audit_result];
		$data['audit_note']			= ( $audit_result=='denial' ) ? $why_denial : '审核通过';
		$data['audit_timestamp']	= date( 'Y-m-d H:i:s' );
		$data['id'] = $id;

		// 审核状态通过，并且是ERP订单，将其发送到ERP系统
		if ( $data['status']==4 && in_array($order['is_erp_order'], [1, 2]) ) {
			$sendNewErpRes = $this-> sendOrderToNewErp( $id ); // 发送到新的ERP系统
			if ( strval($sendNewErpRes['error'])!=='0' ) {
				$this-> jsonFail( '发送新ERP订单失败' );
			}

		}
		if ( $data['status']==4 && in_array($order['is_erp_order'], [1]) ) {
			$sendOldErpRes = $this-> sendOrderToOldErp( $id ); // 发送到老的ERP系统
		}



		$res = $orderModel->audit( $data );
		if ( $res['code']!==0 ) {
			$this-> jsonFail( '审核失败' );
		}
		$this-> jsonSuccess( '审核成功' );
	}

	/**
	 * @brief:  将订单发送至ERP  - 前置条件，订单商品是商品是ERP商品
	 * @param:  $order_id
	 * @return:  
	 */
	private function sendOrderToNewErp( $order_id )
	{
		if ( !$order_id ) {
			return false;
		}
		$orderModel = $this->model( 'order' );
		$getOrderRes = $orderModel->getOrderDetailById( $order_id );
		if ( $getOrderRes['code']!==0 ) {
			return false;
		}
		$order = $getOrderRes['data'];
		if ( !$order ) {
			return false;
		}
		if ( $order['is_delete'] == 1 ) {
			return false;
		}
		if ( $order['is_erp_order'] != 1 ) {  // 只能待审核，才可已审核
			return false;
		}

		$loginUser = $this->getLoginSeller();
	//$loginUser['fgs']; // 分公司代码
	//$loginUser['bmdm']; // 部门代码
	//$loginUser['dm']; // 业务员代码

        $param = array();
		$currentData = date( 'Y-m-d H:i:s' );
				//$param['fphm'] = $this->createErpNewOrderPk();
		$param['fphm'] = $order['order_num'] ;  // 提单单据号
        $param['xz'] = '临调' ;  // 单据性质（临调,直发）
        $param['rq'] = $currentData;  // 单据日期
        $param['kplb'] = '' ;  // 是否开票判断标识
        $param['jsfs'] = '银行转账' ;  // 现金、支票、银行转账等
        $param['jhfs'] = '自提' ;  // 交货方式（自提、代运）
        $param['jhrq'] = $order['add_timestamp'];  // 提单交货日期
        $param['bmmc'] = $loginUser['bmdm'];  // 部门代码 关联bd_bm (广州办事处)
        $param['ywy'] = $loginUser['dm'];  // 提单业务员 关联bd_emp (汪洋)
        $param['bz'] = $order['info'];//'测试新增订单' ;  // 主表备注
        $param['dwdm_1'] = '00002586';//'00002435' ;  // 结算单位代码关联bd_dwtx (湖南大强物资有限公司)
        $param['dwdm_2'] = '00002586';//'00002435' ;  // 结算单位代码关联bd_dwtx
        $param['dwdm_3'] = '00002586';//'00002435' ;  // 结算单位代码关联bd_dwtx
        $param['dwmc_1'] = '大汉电子商务有限公司';//'湖南大强物资有限公司' ;  // 结算单位名称
        $param['dwmc_2'] = '大汉电子商务有限公司';//'湖南大强物资有限公司' ;  // 结算单位名称
        $param['dwmc_3'] = '大汉电子商务有限公司';//'湖南大强物资有限公司' ;  // 结算单位名称
        $param['addr_3'] = '' ;  // 结算单位地址
        $param['fzfee'] = 0;//'35000' ;  // 单据费用
        $param['ck'] = '001' ;  // 仓库代码关联bd_ck (湖南电商库)
        $param['ywlb'] = '临时开单' ;  // 临时开单，合同销售
        $param['fgs'] = '001' ;  // 单据所属公司关联bd_fgs (大大买钢)
        $param['kpfgs'] = '001' ;  // 单据结算公司关联bd_fgs
        $param['czy'] = '钟志勇' ;  // 单据操作员关联sys_users (钟志勇)
        $param['fkfs'] = '' ;  // 付款方式
        $param['fkrq'] = $currentData;  // 要求付款日期
        $param['sh'] = '0' ;  // 单据审核标识，传0
        $param['sysrq'] = $currentData;  // 当前日期，到分秒
        $param['tcxs'] = '1' ;  // 传1
        $param['tjxs'] = '1' ;  // 传1
        $param['effecttime'] = $currentData;  // 传单据日期
        $param['ebptoccflag'] = '1' ;  // 传1
        $param['sysdjlx'] = 'xstd' ;  // 传xstd
    
        $param_data["order"] = $param;
        
        $param = array();
        $param['fphm'] = $order['order_num'];  // 提单单据号XS0011510-00001
        $param['xh'] = '1' ;  // 单据物资明细序号，每张单的序号都是从1开始
        $param['xstdph'] = $order['order_num'].'-00001' ;  // 提单批号，单据每条物资都有唯一批号，如：00000001XS0011510-00001 00000002XS0011510-00001
        $param['pm'] = $order['product_name'];//'螺纹钢' ;  // 品名  =====================================
        $param['cz'] = $order['material_name'];//'HRB335' ;  // 材质
        $param['gg'] = $order['size_name'];//'12*9m' ;  // 规格
        $param['cd'] = $order['factory_name'];//'鄂钢' ;  // 产地
        $param['kcpm'] = $order['product_name'];//'螺纹钢' ;  // 品名
        $param['kccz'] = $order['material_name'];//'HRB335' ;  // 材质
        $param['kcgg'] = $order['size_name'];//'12*9m' ;  // 规格
        $param['kccd'] = $order['factory_name'];//'鄂钢' ;  // 产地
        $param['sl1'] = $order['quantity'];//'10' ;  // 数量
        $param['jldw1'] = '件' ;  // 数量单位
        $param['sl2'] = $order['quantity']*$order['ton'];//'20' ;  // 重量
        $param['jldw2'] = '吨';  // 重量单位
				$countTypeMap = ['1'=>'理计', '2'=>'磅计'];
        $param['jlfs'] = $countTypeMap[$order['count_type']];//'磅计' ;  // 计量方式
        $param['dj'] = $order['price'];//'2100' ;  // 单价
        $param['wsdj'] = (floatval($order['price'])/1.17); //'1709' ;  // 单价/1.17
        //$param['je'] = $order['amount'];  // 金额
        $param['je'] = $order['price'] * $order['ton'] * $order['quantity'];  // 金额 -- 重新实际计算
        $param['wsje'] = $param['wsdj'] * $order['ton'] * $order['quantity'];//'7000';  // 无额金额=无税单价*重量
        $param['se'] = $param['je'] - $param['wsje'];//'1000' ;  // 税额=金额-无税金额
        $param['sl'] = '0.17';  // 税率0.17
        $param['bmmc'] = '014';  // 部门代码关联bd_bm
        $param['hz'] = '0000';  // 传0000
        $param['syr'] = '0000';  // 传0000
		$param['fgs'] = $loginUser['fgs'];//'001' ;  // 单据分公司
        $param['ck'] = '001';  // 仓库代码关联bd_ck
        $param['sysrq'] = $currentData;//date('Y-m-d H:i:s',time()) ;  // 当前日期
    
        $param_data["orderItem"] = $param;
        $data_item = $orderModel->createOrder( $param_data );
    
        Log::notice('-------CMS----TestController------------------------------------test_add3==>>end');
		return $data_item;
        //EC::success(EC_OK, $data_item);
	}


	/**
	 * @brief:  发送订ERP商品订单，到老的ERP系统
	 * @param:  $order_id
	 * @return:  
	 */
	private function sendOrderToOldErp( $order_id )
	{
		if ( !$order_id ) {
			return false;
		}
		$orderModel = $this->model( 'order' );
		$getOrderRes = $orderModel->getOrderDetailById( $order_id );
		if ( $getOrderRes['code']!==0 ) {
			return false;
		}
		$order = $getOrderRes['data'];
		if ( !$order ) {
			return false;
		}
		if ( $order['is_delete'] == 1 ) {
			return false;
		}
		if ( $order['is_erp_order'] != 1 ) {  // 只能待审核，才可已审核
			return false;
		}

		$loginUser = $this->getLoginSeller();


        $p_orderArray = array();
        $p_orderArray['IDCode'] = $loginUser['card_id'];//'431121200006206018';
        $p_orderArray['LPN'] = '湘A9527';//'湘A9527';  --- 暂时找不到写死的
        $p_orderArray['address'] = $order['address'];//'长沙市湘江新区普瑞大道西';
        $p_orderArray['code'] = 'V0003';//'V0003'; --- 暂时找不到写死的

		// seller_id  查公司名，调ERP接口查公司代码
		$companyPK = $this->getErpCompanyPkByUserId( $order['seller_id'] );//;'1006'; 
		$p_orderArray['companyPK'] = $companyPK ? $companyPK : '1006';
		// 湖南大强钢铁贸易有限公司
		
		// user_id 查公司名，调ERP接口查公司代码
		$customerPK = $this->getErpCompanyPkByUserId( $order['user_id'] );//'长沙市洺顺钢材贸易有限公司'; 
		$p_orderArray['customerPK'] = $customerPK ? $customerPK : '1006A110000000001ZMO';
		// 1006A110000000001ZMO
        
        $orderItem = array();
		$orderItem['invbasdocPK'] = '0001A11000000000LEOO';//'0001A11000000000LEOO';  --- 暂时找不到写死的
        $orderItem['otherMoney'] = '';//'90'; 
        $orderItem['price'] = $order['price'];//'2350';
        $orderItem['qty'] = $order['quantity'];//'35';
        $orderItem['storePK'] = $order['erp_warehouse_pk'];//'1006A6100000000002BK'; // 大汉博远库
        $orderItem['taxRate'] = '';//'10';
        $orderItem['weight'] = $order['quantity']*$order['ton'];//'10.56';
        
        $p_orderArray['items'] = $orderItem;
        
        $p_orderArray['mobile'] = $order['tel'];//'13265431549';
        $p_orderArray['person'] = $order['consignee'];//'李四';
        $p_orderArray['recMobile1'] = $order['tel'];//'13265431549';
        $p_orderArray['recMobile2'] = $order['tel'];//'13265431549';
        $p_orderArray['recPerson'] = $order['consignee'];//'张三';
		$userComanyName = $this->getUserCompanyNameByUserId( $order['user_id'] );
        $p_orderArray['receipt'] = $userComanyName ? $userCompanyName : '长沙市洺顺钢材贸易有限公司';//'长沙市洺顺钢材贸易有限公司';
        $p_orderArray['receiptMode'] = '0'; // 不需要发票
        $p_orderArray['transMode'] = '现款现货';//'货到付款';
        $p_orderArray['warehousePK'] = $order['erp_warehouse_pk'];//'1006A6100000000002BK';
        $p_orderArray['zipCode'] = '100010'; // --- 暂时找不到写死的
        
		$url = $this->getErpSoapUrl();
		$data = ErpSoap::addOrder( $url, $p_orderArray );
        return $data;
	}

	private function getErpSoapUrl()
	{
		return 'http://220.168.65.186:8980/DhErpService/services/erpservice?wsdl';
	}

	private function getErpCompanyPkByUserId( $u_id )
	{
		if ( !$u_id ) {
			return false;
		}
		// 获取用户信息(公司名)
		$res = $this->model( 'user' )->getUserById( $u_id );
		$userCompanyName = $res['data']['company'];
		$url = $this->getErpSoapUrl();
		$res = ErpSoap::getCompanyList( $url, $userCompanyName );
		if ( is_array($res[0]) ) {
			return $res[0]['PK'];
		}
		return $res['PK'];
	}

	public function getUserCompanyNameByUserId( $u_id )
	{
		if ( !$u_id ) {
			return false;
		}
		// 获取用户信息(公司名)
		$res = $this->model( 'user' )->getUserById( $u_id );
		return $res['data']['company'];
	}


	/**
	 * @brief:  新建一个新的ERP订单号
	 * @return:  
	 */
	private function createErpNewOrderPk()
	{
		$d = explode( '.', microtime(true) );
		// 规则：  年月日时分秒4位微秒3位随机数
		return date( 'YmdHis' ) . str_pad( $d[1], 4, 0, STR_PAD_LEFT) . str_pad( mt_rand(1, 999), 3, 0, STR_PAD_LEFT);
	}

	/**
   	 * @brief:  订单详情
	 * @return:  
	 */
	private function detail()
	{
		$id = $this->get( 'id', 0 );
		if ( !$id ) {
			$this-> fail( 'not have id' );
		}
		$orderModel = $this->model( 'order' );
		$getOrderRes = $orderModel->getOrderDetailById( $id );
		if ( $getOrderRes['code']!==0 ) {
			$this-> fail( 'getOrder fail' );
		}
		$order = $getOrderRes['data'];
		if ( !$order ) {
			$this-> fail( 'order is empty' );
		}
		if ( $order['is_delete'] == 1 ) {
			$this-> fail( 'order has been deleted' );
		}
		$loginSellerTel = $this->getLoginSellerAccount();
		if ( $order['seller_tel'] != $loginSellerTel ) { 
			$this-> fail( 'not order owner' );
		}

		$data = [];
		$data['order'] = $order;
		$data['queryString'] = $this->getQueryString('id');
		$orderDetail_html = $this->render('orderDetail', $data, true);
		$this->render( 'index', array( 'page_type'=>'orderDetail', 'orderDetail_html'=>$orderDetail_html ) );
	}

	/**
	 * @brief:  修改订单页面
	 * @return:  
	 */
	private function edit()
	{
		$id = $this->get( 'id', 0 );
		if ( !$id ) {
			$this-> fail( '请指定订单ID' );
			//$this-> jsonFail( '请指定订单ID' );
		}
		$orderModel = $this->model( 'order' );
		$getOrderRes = $orderModel->getOrderDetailById( $id );
		if ( $getOrderRes['code']!==0 ) {
			$this-> fail( 'getOrder fail' );
		}
		$order = $getOrderRes['data'];
		if ( !$order ) {
			$this-> fail( 'order is empty' );
		}
		if ( $order['is_delete'] == 1 ) {
			$this-> fail( 'order has been deleted' );
		}
		$loginSellerTel = $this->getLoginSellerAccount();
		if ( $order['seller_tel'] != $loginSellerTel ) { 
			$this-> fail( 'not order owner' );
		}

		$statusConfig = Controller::getConfig( 'status' ); // 获取配置
		$data = [];
		$data['order'] = $order;
		$data['queryString'] = $this->getQueryString('id');
		$data['shipping_type_list'] = $statusConfig['order']['shipping_type'];
		$orderEdit_html = $this->render('orderEdit', $data, true);
		$this->render( 'index', array( 'page_type'=>'orderEdit', 'orderEdit_html'=>$orderEdit_html ) );
		
	}

	/**
	 * @brief:   修改订单，基本信息
	 * @return:  
	 */
	private function doEdit()
	{
		$check = ['id', 'amount', 'consignee', 'shipping_type', 'tel', 'quantity', 'price'];
		$data = [];
		foreach ( $check as $val ) {
			if ( !($data[$val]=$this->post($val)) ) {
				$this-> jsonFail( "not have {$val}" );
			}
		}

		$updateRes = $this->model( 'order' )->modifyOrder( $data );
		if ( $updateRes['code']===0 ) {
			$this-> jsonSuccess( 'update success' );
		}
		$this-> jsonFail( "update fail" );
	}

	/**
	 * @brief:  补填线下支付信息
	 * @return:  
	 */
	private function completeUnlinePayInfo()
	{
		$check = ['id', 'pay_company', 'pay_account', 'payee_company', 'payee_account', 'pay_time'];
		$data = [];
		foreach ( $check as $val ) {
			if ( !($data[$val]=$this->post($val)) ) {
				$this-> jsonFail( "not have {$val}" );
			}
		}

		$updateRes = $this->model( 'order' )->unlinePayComplete( $data );
		if ( $updateRes['code']===0 ) {
			$this-> jsonSuccess( 'update success' );
		}
		$this-> jsonFail( "update fail" );
	}

	//$this-> completeUnlinePayFor(); // 
	
	/**
	 * @brief:  下线支付信息补全
	 * @return:  
	 */
	public function completeUnlinePayFor()
	{
		$id = $this->get( 'id' );
		if ( !$id ) {
			$this-> fail( ' no have id ' );
		}
		$orders = $this->model( 'order' )->getPayBundle( $id );
		//exit;
		// 查询关联的，订单信息
	
		// 渲染模版
		$data = [];
		$data['orders'] = $orders['data'];
		$data['queryString'] = $this->getQueryString('id');
		//$data['shipping_type_list'] = $statusConfig['order']['shipping_type'];
		//$data['city_list'] = $this->model( 'city' )->findAll();
		//$data['factory_list'] = $this->model( 'factory' )->findAll();
		$unlinePay_html = $this->render('unlinePay', $data, true);
		$this->render( 'index', array( 'page_type'=>'unlinePay', 'unlinePay_html'=>$unlinePay_html ) );
	}


	private function delivery()
	{
		$order_id = intval($this->post( 'id' ));
		if ( !$order_id ) {
			$this-> jsonFail( ' no id ' );
		}
		$quantity = intval($this->post( 'quantity' ));
		if ( !$quantity ) {
			$this-> jsonFail( ' no quantity ' );
		}
		$allton = intval($this->post( 'allton' ));
		if ( !$allton ) {
			$this-> jsonFail( ' no allton ' );
		}
		$amount = intval($this->post( 'amount' ));
		if ( !$amount ) {
			$this-> jsonFail( ' no amount ' );
		}

		$data['order_id'] = $order_id;
		$data['quantity'] = $quantity;
		$data['allton'] = $allton;
		$data['amount'] = $amount;
		$res = $this->model('order')->delivery( $data );
		if ( $res['code']!==0 ) {
				$this-> jsonFail( ' fail ' );
		}
		$this-> jsonSuccess( ' success ' );
	}


	//$this-> deliveryInfo(); //  交付信息
	private function deliveryInfo()
	{
		$id = $this-> post( 'id' );
		$info = $this-> model( 'order' )->deliveryInfo( $id );
		//EC::success( EC_OK, $info['data'] );
		$this-> jsonSuccess( '获取成功', $info['data'] );
	}

	private function cancel()
	{
		$order_id = $this->post( 'id' );
		if ( !$order_id ) {
			$this->jsonFail( 'no have order_id' );
		}
		$res = $this->model( 'order' )->cancel( $order_id );
		if ( $res['code'] !== 0 ) {
			$this->jsonFail( ' cancel order fail ' );
		}
		$this-> jsonSuccess( ' cancel success ' );
	}

	private function done()
	{
		$order_id = $this->post( 'id' );
		if ( !$order_id ) {
			$this->jsonFail( 'no have order_id' );
		}
		$res = $this->model( 'order' )->done( $order_id );
		if ( $res['code'] !== 0 ) {
			$this->jsonFail( ' done order fail ' );
		}
		$this-> jsonSuccess( ' done success ' );
	}

	/**
	 * @brief:  检测登录
	 * @return:  
	 */
	protected function init(  )
	{
		PassportController::checklogin(); // 暂时不检测登录
	}


	private function doInvoicing()
	{
		$check = ['id', 'invoicing_status'];
		$data = [];
		foreach ( $check as $val ) {
			if ( !($data[$val]=$this->post($val)) ) {
				$this-> jsonFail( "not have {$val}" );
			}
		}

		$updateRes = $this->model( 'order' )->modifyOrder( $data );
		if ( $updateRes['code']===0 ) {
			$this-> jsonSuccess( 'update success' );
		}
		$this-> jsonFail( "update fail" );
	}
}
