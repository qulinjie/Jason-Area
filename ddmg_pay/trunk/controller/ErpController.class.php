<?php
/**
 * @file:  ErpController.class.php
 * @brief: 对接云钢网Erp接口系统
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-10-27
 */

class ErpController extends BaseController
{
	//private $testUrl = "http://220.168.65.186:8980/DhErpService/services/erpservice?wsdl";
	//private $url = "http://124.232.142.207:8080/DhErpService/services/erpservice?wsdl";

	public function handle( $params=[] )
	{
		if ( !$params ) {
			$this-> itemsList();
		}
	   	else switch( $params[0] )
		{
			case 'list':// 根据材质ID，获取规格
				$this-> itemsList();
				break;

			case 'onsale':// 上架
				$this-> onsale();
				break;

			default:// 默认商品列表
				Log::error('page not found');
				EC::page_not_found();
				break;
		}
	}

	protected static $ErpServiceUrl;
	private function getErpServiceUrl(  )
	{
		if ( self::$ErpServiceUrl ) {
			return self::$ErpServiceUrl;
		}
		$conf = Controller::getConfig('erp_service');
		if ( !$conf['soapUrl'] ) {
			Log::error( ' erp_service.ini.php ' );
			return '';
		}
		return self::$ErpServiceUrl = $conf['soapUrl'];
	}

	/**
	 * @brief:   商品列表
	 * @return:  
	 */
	private function itemsList()
	{
		$loginUser = $this->getLoginSeller();

		$condition = $this->getConditionArr();

		if ( !$loginUser['company'] ) {
			$this-> fail( ' no login user company! ', Router::getBaseUrl().'item' );
		}


		//var_dump($loginUser['company']);
		$companyInfo = ErpSoap::getCompanyList( $this->getErpServiceUrl(), $loginUser['company']);
		if ( !$companyInfo['PK'] ) {
			$this-> fail( ' no have this company! ', Router::getBaseUrl().'item' );
		}
		//var_dump($companyInfo);

		$warehouseList = ErpSoap::getWarehouseList( $this->getErpServiceUrl(), $companyInfo['PK'] );
		$warehouseMap = [];
		foreach ( $warehouseList as $warehouse ) {
				$warehouseMap[$warehouse['PK']] = $warehouse;
		}
		//var_dump($warehouseMap['1022A61000000000033A']);
		
		$categoryList = ErpSoap::getCategoryList( $this->getErpServiceUrl(), '01' );

		if ( $condition['condition']['warehouse_id'] ) {
			$items = ErpSoap::getStoreList( $this->getErpServiceUrl(), $condition['condition']['warehouse_id'], $companyInfo['PK'] );
			do {
				if ( !$items ) break;

				if ( is_array($items[0]) ) {
					foreach ( $items as &$item ) {
						//var_dump($warehouseMap);
						$item['address'] = $warehouseMap[$item['warehousePK']]['address'];
						$item['warehouse_name'] = $warehouseMap[$item['warehousePK']]['name'];
					}
				} else {
					$items['address'] = $warehouseMap[$items['warehousePK']]['address'];
					$items['warehouse_name'] = $warehouseMap[$items['warehousePK']]['name'];
				}
			}
			while ( 0 );

		}
		//$storeList = ErpSoap::getStoreList( $this->getErpServiceUrl(), $warehouseList[0]['PK'], $company['PK'] );
		//var_dump($storeList);

		$data = [];
		$data['warehouseList'] = $warehouseList;

		$data['productList'] = $categoryList; // ERP里的分类就是目前系统里的品名


		$data['queryString'] = $this->getQueryString();
		$data['params'] = $condition['params'];
		$data['items'] = is_array($items[0]) ? $items : ( $items ? [$items] : '');
		$data['city_list'] = $this->model( 'city' )->findAll();
		$data['factory_list'] = $this->model( 'factory' )->findAll();
		$data['pager_html'] = $pager_html;
		$data['page'] = $condition['condition']['page'];
		$data['numPerPage'] = $condition['condition']['count'];
		$erpItemList_html = $this->render('erpItemList', $data, true);
		$this->render( 'index', array( 'page_type'=>'erpItemList', 'erpItemList_html'=>$erpItemList_html ) );



		return false;
		$condition = $this->getConditionArr();

		$itemModel = $this->model( 'item' );

		$getCountRes = $itemModel->getCount( $condition['condition'] );
		$total = $getCountRes['data'];

		// 分页
		//$page = ( int )$this->get( 'page', 1 );
		//$numPerPage = 2;
		$pager_html = $this->getPageHtml( $condition['condition']['page'], $total, $condition['condition']['count'] );

		// 获取数据
		//$items = $itemModel->getItemList( $condition['whereK'], $condition['whereV'], $page, $numPerPage );
		$getListRes = $itemModel->getList( $condition['condition'] );
		$items = $getListRes['data'];


		/*
		$itemModel = $this->model( 'item' );
		$session =  $this->instance('session');	

		// 获取查询条件
		$condition = $this->getConditionArry();

		// 总条数
		$total = $itemModel->count( null, '1', $condition['whereK'], $condition['whereV'] );

		// 分页
		$page = ( int )$this->get( 'page', 1 );
		$numPerPage = 2;
		$pager_html = $this->getPageHtml( $page, $total, $numPerPage );

		// 获取数据
		$items = $itemModel->getItemList( $condition['whereK'], $condition['whereV'], $page, $numPerPage );
		 */
		
		$data = [];
		$data['queryString'] = $this->getQueryString();
		$data['params'] = $condition['params'];
		$data['items'] = $items;
		$data['city_list'] = $this->model( 'city' )->findAll();
		$data['factory_list'] = $this->model( 'factory' )->findAll();
		$data['pager_html'] = $pager_html;
		$data['page'] = $condition['condition']['page'];
		$data['numPerPage'] = $condition['condition']['count'];
		$itemList_html = $this->render('itemList', $data, true);
		$this->render( 'index', array( 'page_type'=>'itemList', 'itemList_html'=>$itemList_html ) );
	}

	private function getConditionArr()
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

		$warehouse_id = $this->get('warehouse_id' );
		if ( $warehouse_id ) {
			$conditionArr['warehouse_id'] = $warehouse_id;
			$params['warehouse_id'] = $warehouse_id;
		}

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

		$is_on_sale = isset($_GET['is_on_sale']) ? $_GET['is_on_sale'] : '';
		if ( $is_on_sale >=0 && $is_on_sale!=='' ) {
			$conditionArr['is_on_sale'] = (int)$is_on_sale;
			$params['is_on_sale'] = (int)$is_on_sale;
		}

		/*
		$count = (int)$this->get('count', 100 );
		if ( $count ) {
			$conditionArr['count'] = $count;
			$params['count'] = $count;
		}

		$page = (int)$this->get('page', 1 );
		if ( $page ) {
			$conditionArr['page'] = $page;
			$params['page'] = $page;
		}
		 */

		return ['condition'=>$conditionArr, 'params'=>$params];
	}

	/**
	 * @brief:  ERP商品上架，实质上是将ERP接口的产品信息, 添加到本地数据库中去
	 * @return:  
	 */
	private function onsale()
	{
		//$checkList = ['product_name', 'material_name', 'size_name', 'factory_name', 'city_name', 'delivery_point', 'price', 'price_type', 'ton', 'count_type', 'inventory'];
		$checkList = ['product_name', 'material_name', 'size_name', 'factory_name', 'city_name', 'delivery_point', 'price', 'price_type', 'ton', 'count_type', 'inventory', 'productPK', 'warehousePK'];
		$data = [];
		if ( !$_POST['city_name'] ) {
			$_POST['city_name'] = '长沙'; // ERP接口数据，城市名为空的时候，默认长沙市
		}
		foreach ( $checkList as $val ) {
			if ( !$_POST[$val] ) {
				$this->fail( " no have {$val} " );
			}
			$data[$val] = $_POST[$val];
		}
		$addErpItemRes = $this->model( 'item' )->addErpItem( $data );
		if ( $addErpItemRes['code']===0 ) {
			EC::success(EC_OK);
			//$this-> success( '添加成功', Router::getBaseUrl()."item" ); // 添加成功，返回商品列表 
		}
		EC::fail( EC_ADD_FAI );
		//$this-> fail( '添加失败', Router::getBaseUrl().'item/add' );
	}



	/**
	 * @brief:  检测登录
	 * @return:  
	 */
	protected function init(  )
	{
		PassportController::checklogin();  // 检测登录
	}
}
