<?php
/**
 * @file:  ItemController.class.php
 * @brief: 卖家-网页版，商品管理
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-18
 */

class ItemController extends BaseController
{
	protected $session;

	public function handle( $params=[] )
	{
		if ( !$params ) {
			$this-> itemsList();
		}
	   	else switch( $params[0] )
		{
			case 'getDAddr': 
				$this-> getDeliveryAddressList(); 
				break;
			 
			case 'import':// 批量导入商品
				$this-> batchImportItems();
				break;
			case 'doImport':// 批量导入商品
				$this-> doBatchImportItems();
				break;

			case 'add':// 添加商品页
				$this-> add();
				break;
			case 'doAdd':// 执行添加
				$this-> doAdd();
				break;
			
			case 'edit':// 编辑商品页
				$this-> edit();
				break;
			case 'doEdit':// 编辑商品页
				$this-> doEdit();
				break;
			
			case 'delete':// 删除商品
				$this-> delete();
				break;

			case 'list':// 商品列表
				$this-> itemsList();
				break;
			
			case 'toggle':// 开关：上下架
				$this-> toggle();
				break;

			case 'batchOnsale':// 批量上架
				$this-> batchOnsale();
				break;
			case 'batchUnsale':// 批量下架
				$this-> batchUnsale();
				break;

			case 'factory':// 根据分类ID，获取分类下的品名
				$this-> getFactoryJson();
				break;
			case 'city':// 根据分类ID，获取分类下的品名
				$this-> getCityJson();
				break;

			case 'category':// 根据分类ID，获取分类下的品名
				$this-> getCategoryJson();
				break;
			case 'product':// 根据分类ID，获取分类下的品名
				$this-> getProductsByCategoryId();
				break;
			case 'material':// 根据品名ID，获取材质
				$this-> getMaterialByProductId();
				break;
			case 'size':// 根据材质ID，获取规格
				$this-> getSizeByMaterialId();
				break;

			default:// 默认商品列表
				Log::error('page not found');
				EC::page_not_found();
				break;
		}
	}


	private function addDeliveryAddress()
	{
		$name = $this->post( 'name' );
		$res = $this->model( 'item' )->addDAddr( ['name'=>$name] );
		if ( $res['code']===0 ) {
			EC::success(EC_OK, $res['data']);
			//$this-> success( '添加成功', Router::getBaseUrl()."item" ); // 添加成功，返回商品列表 
		} 
		EC::fail( '失败' );
		//$this-> fail( '添加失败', Router::getBaseUrl().'item/add' );
	}

	private function updateDeliveryAddress()
	{
		$id = $this->post( 'id' );
		$name = $this->post( 'name' );
		if ( !$id || !$name ) {
			EC::fail( '失败' );
			//$this-> fail( '缺少参数', Router::getBaseUrl().'item/add' );
		}
		$res = $this->model( 'item' )->updateDAddr( ['id'=>$id, 'name'=>$name] );
		if ( $res['code']===0 ) {
			EC::success( EC_OK );
			//$this-> success( '修改成功', Router::getBaseUrl()."item" ); // 添加成功，返回商品列表
		} 
		EC::fail( '失败' );
		//$this-> fail( '修改失败', Router::getBaseUrl().'item/add' );
	}
	private function delDeliveryAddress()
	{
		$id = $this->post( 'id' );
		if ( !$id ) {
			EC::fail( '失败' );
			//$this-> fail( '修改失败', Router::getBaseUrl().'item/add' );
		}
		$res = $this->model( 'item' )->delDAddr( ['id'=>$id] );
		if ( $res['code']===0 ) {
			EC::success( EC_OK );
			//$this-> success( '删除成功', Router::getBaseUrl()."item" ); // 添加成功，返回商品列表
		} 
		//$this-> fail( '删除失败', Router::getBaseUrl().'item/add' );
		EC::fail( '失败' );
	}

	private function getDeliveryAddressList()
	{
		$res = $this-> model( 'item' )->getDAddr();
		$data = $res['data'];
		EC::success( EC_OK, $data );
		//die( json_encode( $data ) );
	}

	private function getFactory()
	{
		$res = $this-> model( 'item' )->getFactoryList();
		return $res['data'];
	}

	private function getFactoryJson()
	{
		die( json_encode( $this->getFactory() ) );
	}

	private function getFactoryMap()
	{
		$factory = $this->getFactory();
		if ( !$factory['factory_list'] ) {
			return false;
		}
		$reData = [];
		foreach ( $factory['factory_list'] as $val ) {
			$reData[$val['id']] = $val['name'];
		}
		return $reData;
	}

	private function getCity()
	{
		$res = $this-> model( 'item' )->getCityList();
		return $res['data'];
	}

	private function getCityJson()
	{
		die( json_encode( $this->getCity() ) );
	}

	private function getCityMap()
	{
		$city = $this->getCity();
		if ( !$city['city_list'] ) {
			return false;
		}
		$reData = [];
		foreach ( $city['city_list'] as $val ) {
			$reData[$val['id']] = $val['name'];
		}
		return $reData;
	}

	private function getWarehouseMap()
	{
		$warehouse = $this->getWarehouse();
		if ( !$warehouse ) {
			return false;
		}
		$reData = [];
		foreach ( $warehouse as $val ) {
			$reData[$val['id']] = $val['name'];
		}
		return $reData;
	}

	private function getWarehouse()
	{
		$res = $this-> model( 'item' )->getDAddr();
		return $res['data'];
	}

	/**
	 * @brief:  获取商品分类
	 * @return:  
	 */
	private function getCategory()
	{
		$res = $this-> model( 'item' )->getCategoryList();
		return $res['data'];
	}

	private function getCategoryJson(  )
	{
		die( json_encode( $this->getCategory() ) );
	}

	private function getCategoryMap()
	{
		$category = $this->getCategory();
		if ( !$category ) {
			return false;
		}
		$reData = [];
		foreach ( $category as $val ) {
			$reData[$val['id']] = $val['name'];
		}
		return $reData;
	}

	private function getProductsMapByCategoryId( $id )
	{
		$res = $this-> model( 'item' )->getProductList( $id );
		if ( !$res['data'] ) {
			return false;
		}
		$reData = [];
		foreach ( $res['data'] as $val ) {
			$reData[$val['id']] = $val['name'];
		}
		return $reData;
	}

	/**
	 * @brief:   根据材质ID，获取规格
	 * @return:  
	 */
	private function getSizeByMaterialId()
	{
		$material_id = $this-> get( 'material_id' );
		if ( !$material_id ) { // 需要传产品分类ID
			Log::error('request param error!');
			EC::fail( EC_PAR_BAD );
		}
		$res = $this->model( 'item' )->getSizeList( $material_id );
		$data = $res['data'];
		die(json_encode( $data ));
	}

	private function getSizeMapByMaterialId( $id )
	{
		$res = $this->model( 'item' )->getSizeList( $id );
		if ( !$res['data'] ) {
			return false;
		}
		$reData = [];
		foreach ( $res['data'] as $val ) {
			$reData[$val['id']] = $val['size'];
		}
		return $reData;
	}


	/**
	 * @brief:   根据分类ID，获取分类下的品名
	 * @return:  
	 */
	private function getProductsByCategoryId()
	{
		$category_id = $this-> get( 'category_id' );
		if ( !$category_id ) { // 需要传产品分类ID
			Log::error('request param error!');
			EC::fail( EC_PAR_BAD );
		}
		$res = $this-> model( 'item' )->getProductList( $category_id );
		$data = $res['data'];
		die(json_encode( $data ));
	}

	/**
	 * @brief:   根据品名ID，获取材质
	 * @return:  
	 */
	private function getMaterialByProductId()
	{
		$product_id = $this-> get( 'product_id' );
		if ( !$product_id ) { // 需要传产品分类ID
			Log::error('request param error!');
			EC::fail( EC_PAR_BAD );
		}
		$res = $this-> model( 'item' )->getMaterialList( $product_id );
		$data = $res['data'];
		die(json_encode( $data ));
	}

	private function getMaterialMapByProductId( $id )
	{
		$res = $this-> model( 'item' )->getMaterialList( $id );
		if ( !$res['data'] ) {
			return false;
		}
		$reData = [];
		foreach ( $res['data'] as $val ) {
			$reData[$val['id']] = $val['name'];
		}
		return $reData;
	}

	/**
	 * @brief:   添加商品页
	 * @return:  
	 */
	private function add()
	{
		$data = [];
		$cityRes = $this-> model( 'item' )->getCityList();
		$data['city_list'] = $cityRes['data']['city_list'];

		$factoryRes = $this-> model( 'item' )->getFactoryList();
		$data['factory_list'] = $factoryRes['data']['factory_list'];

		$DAddrRes = $this-> model( 'item' )->getDAddr();
		$data['delivery_address_list'] = $DAddrRes['data'];

		$config = Controller::getConfig( 'status' ); // ROOT/conf/status.ini.php
		$data['count_type_list'] = $config['item']['count_type'];
		$data['price_type_list'] = $config['item']['price_type'];

		$itemAdd_html = $this->render('itemAdd', $data, true);
		$this->render( 'index', array( 'page_type'=>'itemAdd', 'itemAdd_html'=>$itemAdd_html ) );
	}

	/**
	 * @brief:  执行添加
	 * @return:  
	 */
	private function doAdd()
	{
		$category_id = $this->post('category_id');
		$product_id = $this->post('product_id');
		$material_id = $this->post('material_id');
		$size_id = $this->post('size_id');
		$factory_id = $this->post('factory_id');
		$city_id = $this->post('city_id');
		$price = $this->post('price');
		$ton = $this->post('ton');
		$inventory = $this->post('inventory');
		$count_type = $this->post('count_type');
		$price_type = $this->post('price_type');
		$delivery_address_id = $this->post('delivery_address_id');

		$check = [
			'category_id', 
			'product_id', 
			'material_id', 
			'size_id', 
			'factory_id', 
			'price', 
			'ton', 
			'inventory', 
			'count_type', 
			'price_type', 
			'delivery_address_id', 
		];
		foreach ( $check as $val ) {
			if ( !$$val ) {
				$this-> fail( "{$val}不能为空", Router::getBaseUrl().'item/add' );
			}
		}

		/*
		 *  这段要移到SERVER 端，
		 * */
		//检查是否存在该规格
		if(!$size_info = $this->model('size')->getSize(array('size'=>$size_id,'material_id'=>$material_id))){
			$really_size_id = $this->model('id')->getSizeId();
			$this->model('size')->createSize(array('id'=> $really_size_id,'size'=>$size_id,'material_id'=>$material_id));
		}else{
			$really_size_id = $size_info[0]['id'];
		}

		$data = [
			'id' => $id, 
			'category_id' => $category_id, 
			'product_id' => $product_id, 
			'material_id' => $material_id, 
			'size_id' => $really_size_id,
			'factory_id' => $factory_id, 
			'city_id' => $city_id, 
			'price' => $price, 
			'ton' => $ton, 
			//'delivery_point' => $delivery_point, 
			'inventory' => $inventory, 
			//'merchant_id' => $merchant_id, 
			'delivery_address_id' => $delivery_address_id, 
			'count_type' => $count_type, 
			'price_type' => $price_type 
		];

		$res = $this->model( 'item' )->addItem( $data );
		if ( $res['code']===0 ) {
			$this-> success( '添加成功', Router::getBaseUrl()."item" ); // 添加成功，返回商品列表
		} 
		$this-> fail( '添加失败', Router::getBaseUrl().'item/add' );

	}

	/**
	 * @brief:  编辑商品页
	 * @return:  
	 */
	private function edit()
	{
		$id = $this->get( 'id', 0 );
		if ( !$id ) {
			$this-> fail( '请指定商品ID' );
		}
		
		$itemModel = $this->model( 'item' );
		$res = $itemModel->getItemById( $id );
		if ( $res['code']!==0 ) {
			$this-> fail( $res['msg'], Router::getBaseUrl().'item/add' );
		}

		/*
		$item = $itemModel->getItemDetailById( $id );
		if ( !$item ) {
			$this-> fail( '商品不存在' );
		}
		if ( $item['is_delete'] == 1 ) {
			$this-> fail( '商品已被删除' );
		}
		/*  目前在售商品可以修改
		if ( $item['is_on_sale'] == 1 ) {
			$this-> fail( '修改商品请先下架商品' );
		}
		$loginSellerId = $this->getLoginSellerId();
		if ( $item['seller_id'] != $loginSellerId ) { 
			$this-> fail( '只能修改自己的商品' );
		}
		 */

		$config = Controller::getConfig( 'status' ); // ROOT/conf/status.ini.php
		$data = [];
		$data['count_type_list'] = $config['item']['count_type'];
		$data['price_type_list'] = $config['item']['price_type'];
		$data['item'] = $res['data'][0];
		$data['address_list'] = $itemModel->getDAddr()['data'];
		$itemEdit_html = $this->render('itemEdit', $data, true);
		$this->render( 'index', array( 'page_type'=>'itemEdit', 'itemEdit_html'=>$itemEdit_html ) );
	}

	/**
	 * @brief:   编辑商品页
	 * @return:  
	 */
	private function doEdit()
	{
		$id = $this->post( 'id', 0 );
		if ( !$id ) {
			//$this-> fail( '请指定商品ID' );
			$this-> jsonFail( '请指定商品ID' );
		}
		$price = $this->post( 'price', '' );
		if ( !$price ) {
			//$this-> fail( '请填写商品价格' );
			$this-> jsonFail( '请填写商品价格' );
		}
		$ton = $this->post( 'ton', '' );
		if ( !$ton ) {
			//$this-> fail( '请填写每件吨重', $ton );
			$this-> jsonFail( '请填写每件吨重' );
		}
		$inventory = $this->post( 'inventory', '' );
		if ( !$inventory ) {
			//$this-> fail( 'no have inventory ' );
			$this-> jsonFail( '请填写库存' );
		}
		$delivery_point = $this->post( 'delivery_point', '' );
		if ( !$delivery_point ) {
			//$this-> fail( '请填写交割地' );
			$this-> jsonFail( '请填写交割地' );
		}

		$data = [];
		$data['id'] = $id;
		$data['price'] = $price;
		$data['ton'] = $ton;
		$data['delivery_point'] = $delivery_point;
		$data['inventory'] = $inventory;

		$res = $this->model( 'item' )->update( $data );
		if ( $res['code']===0 ) {
			/*
			 *  还差个，记录操作日子
			 *
			 * */
			$queryString = $this-> getQueryString( 'id' );
			//$this-> success( '修改成功', Router::getBaseUrl()."item?{$queryString}" );
			$this-> jsonSuccess( '修改成功' );
		}
		//$this-> fail( '修改失败' );
		$this-> jsonFail( '修改失败' );


		/*
		$itemModel = $this->model( 'item' );
		$item = $itemModel->getItemDetailById( $id );
		if ( !$item ) {
			$this-> fail( '商品不存在' );
		}
		if ( $item['is_delete'] == 1 ) {
			$this-> fail( '商品已被删除' );
		}
		if ( $item['is_on_sale'] == 1 ) {
			$this-> fail( '删除在售商品请先下架' );
		}
		$loginSellerId = $this->getLoginSellerId();
		if ( $item['seller_id'] != $loginSellerId ) { 
			$this-> fail( '只能删除自己的商品' );
		}

		$update = [];
		foreach ( ['price', 'ton', 'delivery_point'] as $val ) {
			if ( $item[$val] != $$val ) {
				$update[$val] = $$val;
			}
		}
		if ( empty( $update ) ) {
			$this-> fail( '无需修改' );
		}

		$update['mod_timestammp'] = date( 'Y-m-d H:i:s' );
		$ok = $itemModel->update( $update, ['id=?'], [$id] );
		 */

	}

	/**
	 * @brief:  删除商品
	 * @return:  
	 */
	private function delete()
	{
		$id = $this->post( 'id', 0 );
		if ( !$id ) {
			Log::error( __METHOD__ . ' !$id ' );
	        EC::fail(EC_PAR_BAD);
		}
		$res = $this->model( 'item' )->delete( $id );
		if ( $res['code'] === 0 ) {
			EC::success(EC_OK, $res['data']);
		}else{
	        EC::fail($res['code']);
		}
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

		$is_on_sale = isset($_GET['is_on_sale']) ? $_GET['is_on_sale'] : '';
		if ( $is_on_sale >=0 && $is_on_sale!=='' ) {
			$whereK[] = 'is_on_sale=?';
			$whereV[] = $is_on_sale;
			$params['is_on_sale'] = (int)$is_on_sale;// 必须转整配合模版内条件判断
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
	 * @brief:   商品列表
	 * @return:  
	 */
	private function itemsList()
	{

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
		//var_dump($getListRes);
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

	/**
	 * @brief:  开关：上下架
	 * @return:  
	 */
	private function toggle()
	{
		$id = $this->post( 'id', 0 );
		if ( !$id ) {
	        EC::fail(EC_PAR_BAD);
		}
		
		$res = $this->model( 'item' )->toggle( $id );
		if ( $res['code'] === 0 ) {
			EC::success(EC_OK, $res['data']);
		}else{
	        EC::fail($res['code']);
		}
		/*
		$itemModel = $this-> model( 'item' );
		$item = $itemModel->find( $id );
		if ( !$item ) {
			$this-> fail( '商品不存在' );
		}
		if ( $item['is_delete'] == 1 ) {
			$this-> fail( '商品已被删除' );
		}

		$loginSellerId = $this->getLoginSellerId();
		if ( $item['seller_id'] != $loginSellerId ) { 
			$this-> fail( '只能操作自己的商品' );
		}

		// 更新状态
		$status = $item['is_on_sale'] ? 0 : 1;
		$update['is_on_sale'] = $status;

		// 更新上下架时间
		$now = date( 'Y-m-d H:i:s' );
		if ( $status ) {
			$update['on_sale_time'] = $now;
		} else {
			$update['on_sale_time'] = $now;
		}

		$ok = $itemModel->update( $update, ['id=?'], [$id] );
		if ( $ok ) {
			$queryString = $this-> getQueryString( 'id' );
			$this-> success( '操作成功', Router::getBaseUrl()."item?{$queryString}" );
		}
		$this-> fail( '操作失败' );
		 */
	}


	private function batchOnsale(  )
	{
		if (!$_POST['idArr'] || !is_array( $_POST['idArr'] )) {
	        EC::fail(EC_PAR_BAD);
		}
		
		$res = $this->model( 'item' )->batchOnsale( $_POST['idArr'] );
		if ( $res['code'] === 0 ) {
			EC::success(EC_OK, $res['data']);
		}else{
	        EC::fail($res['code']);
		}
	}

	private function batchUnsale(  )
	{
		if ( !$_POST['idArr'] || !is_array( $_POST['idArr'] ) ) {
	        EC::fail(EC_PAR_BAD);
		}
		$res = $this->model( 'item' )->batchUnsale( $_POST['idArr'] );
		if ( $res['code'] === 0 ) {
			EC::success(EC_OK, $res['data']);
		} else {
	        EC::fail($res['code']);
		}
	}


	/**
	 * @brief:  上传CSV格式文件，批量导入商品
	 * @return:  
	 */
	protected function batchImportItems()
	{
		@set_time_limit(0);
		@ini_set( 'memory_limit', '1024M' );
		 /*
		  * 未成功上传
		  */
		if ( 0 !== $_FILES['importItemsCSV']['error'] ) {
			$error = array(
				1 => '上传的文件过大', 
				2 => '上传的文件过大', 
				3 => '文件只有部分被上传', 
				4 => '请选择上传文件', 
				6 => '找不到临时文件夹', 
				7 => '文件写入失败', 
		   	);
		//	$this-> showMsg( $error[$_FILES['importCSV']['error']], "{$this->url}/kefu/Index.html", 2, 0 );
			$this-> fail( $error[$_FILES['importItemsCSV']['error']] );
		}

		/*
		 * 类型不是CSV
		 */
		$fileInfo = pathinfo( $_FILES['importItemsCSV']['name'] );
		if ( 'csv' != strtolower($fileInfo['extension']) ) {
			$this-> fail( '请先将[ Excel ]文件另存为[ csv ]格式文件' );
		}


		/*
		 * 过滤无效行
		 */
		$importData = array();
		$k = 0;
		if ( ($handle = fopen( $_FILES['importItemsCSV']['tmp_name'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$k++;
				//   跳过表头 -- 设置表头行数
				if ( $k<=1 ) { continue; }
				//   跳过空无效行，订单号，收件人，收件电话，收件地址 --  设置表格必填项 1, 2, 3, 4  第一列，第二列
				//if ( $data[1]=='' ||  $data[2]==''  ||  $data[4]=='' ||  $data[9]=='' ||  $data[18]=='' ) { continue; }

				foreach ( $data as $dk=>$dv ) {
					//规格不一定全是数字
					/*if ( $dk==3 ) {
						$dv = sprintf( '%.2f', $dv );
					}*/
					// 做一些过滤
					$dv = trim( $dv );
					$dv = trim( $dv, "'" );
					$dv = trim( $dv, '"' );
					$dv = trim( $dv, '‘' );
					$dv = trim( $dv, '’' );
					// 转义过滤
					$data[$dk] = addslashes($dv);
					// 字符转码
					$data[$dk] = iconv( 'gbk', 'utf-8', $data[$dk] );
				}
				$importData[] = $data;
			}
			fclose($handle);
		} else {
			$this-> fail( '解析失败' );
		}

		// 导入表达为空
		if ( empty($importData) ) {
			$this-> fail( '导入的CSV文件为空' );
		}

		// 转换数字索引为关联索引
		$importData = $this->convertKey( $importData );

		// 获取分类ID映射
		$categoryMap = $this->getCategoryMap();
		$mapCategory = array_flip( $categoryMap );

		// 获取钢厂ID映射
		$factoryMap = $this->getFactoryMap();
		$mapFactory = array_flip( $factoryMap );
		
		// 获取城市ID映射
		$cityMap = $this-> getCityMap();
		$mapCity = array_flip( $cityMap );

		//仓库地址ID映射
		$warehouseMap = $this->getWarehouseMap();
		$mapWarehouse = array_flip($warehouseMap);

		// 获取状态ID映射
		$config = Controller::getConfig( 'status' ); // ROOT/conf/status.ini.php
		$countTypeMap = $config['item']['count_type'];
		$mapCountType = array_flip( $countTypeMap );
		$priceTypeMap = $config['item']['price_type'];
		$mapPriceType = array_flip( $priceTypeMap );


		foreach ( $importData as $k=>$val ) {
			do{
				// 验证分类
				if ( !in_array( $val['category_name'], $categoryMap ) ) {
					$importData[$k]['err'][] = ' 厂家不存在 category_name is error ';
					continue;
				}
				$importData[$k]['category_id'] = $mapCategory[$val['category_name']];
				// 验证品名
				$productMap = $this-> getProductsMapByCategoryId($mapCategory[$val['category_name']]);
				if ( !$productMap ) {
					$importData[$k]['err'][] = ' 品名查询失败 product_name query fail ';
					continue;
				}
				$mapProduct = array_flip( $productMap );
				if ( !in_array( $val['product_name'], $productMap ) ) {
					$importData[$k]['err'][] = ' 品名不存在 product_name is error ';
					continue;
				}
				$importData[$k]['product_id'] = $mapProduct[$val['product_name']];
				// 验证材质 material_name
				$materialMap = $this-> getMaterialMapByProductId( $mapProduct[$val['product_name']] );
				if ( !$materialMap ) {
					$importData[$k]['err'][] = ' 材质查询失败 materialMap query fail ';
					continue;
				}
				$mapMaterial = array_flip( $materialMap );
				if ( !in_array( $val['material_name'], $materialMap ) ) {
					$importData[$k]['err'][] = ' 材质不存在 material_name is error ';
					continue;
				}
				$importData[$k]['material_id'] = $mapMaterial[$val['material_name']];
				// 验证规格 size_name 不存在则写入
				$sizeMap = $this-> getSizeMapByMaterialId( $mapMaterial[$val['material_name']] );
				$mapSize = array_flip( $sizeMap );
				if(!$sizeMap || !in_array( $val['size_name'], $sizeMap )){
					$importData[$k]['size_id'] = $this->model('id')->getSizeId();
					$this->model('size')->createSize(array('id' => $importData[$k]['size_id'],'size' => $val['size_name'],'material_id' => $importData[$k]['material_id']));
				}else{
					$importData[$k]['size_id'] = $mapSize[$val['size_name']];
				}
				/*if ( !$sizeMap ) {
					$importData[$k]['err'][] = ' 规格查询失败 sizeMap query fail ';
					continue;
				}
				$mapSize = array_flip( $sizeMap );
				if ( !in_array( $val['size_name'], $sizeMap ) ) {
					$importData[$k]['err'][] = ' 规格不存在 size_name is error ';
					continue;
				}
				$importData[$k]['size_id'] = $mapSize[$val['size_name']];*/

			} while(0);


			// 验证厂家 factory_name
			if ( !in_array( $val['factory_name'], $factoryMap ) ) {
				$importData[$k]['err'][] = ' 厂家不存在 factory_name is error ';
				continue;
			}
			$importData[$k]['factory_id'] = $mapFactory[$val['factory_name']];
			// 验证城市 city_name
			if ( !in_array( $val['city_name'], $cityMap ) ) {
				$importData[$k]['err'][] = ' 城市不存在 city_name is error ';
				continue;
			}
			$importData[$k]['city_id'] = $mapCity[$val['city_name']];
			// 验证单价
			if ( !is_numeric( $val['price'] ) || !$val['price'] ) {
				$importData[$k]['err'][] = ' 价格不是数字 price is not number ';
				continue;
			}
			// 价格类型 price_type_name
			if ( !in_array( $val['price_type_name'], $priceTypeMap ) ) {
				$importData[$k]['err'][] = ' 计价类型错误 price_type_name error ';
				continue;
			}
			$importData[$k]['price_type'] = $mapPriceType[$val['price_type_name']];
			// 重量 ton
			if ( !is_numeric($val['ton']) || !$val['ton'] ) {
				$importData[$k]['err'][] = ' 重量错误 ton error ';
				continue;
			}
			// 库存 inventory
			if ( !is_numeric($val['inventory']) || !$val['inventory'] ) {
				$importData[$k]['err'][] = ' 库存错误 ton error ';
				continue;
			}
			// 计重类型 count_type_name
			if ( !in_array( $val['count_type_name'], $countTypeMap ) ) {
				$importData[$k]['err'][] = ' 计重类型错误 count_type_name error ';
				continue;
			}
			$importData[$k]['count_type'] = $mapCountType[$val['count_type_name']];

			//验证交割仓库
			if ( !in_array( $val['delivery_point'], $warehouseMap) ) {
				$importData[$k]['err'][] = ' 交割仓库不存在 delivery_point is error ';
				continue;
			}
			$importData[$k]['delivery_point'] = $val['delivery_point'];
		}
		//var_dump($importData);
		//exit;

		$data['importData'] = $importData;
		$importResult_html = $this->render('importResult', $data, true);
		$this->render( 'index', array( 'page_type'=>'importRes', 'importResult_html'=>$importResult_html ) );

	}


	protected function doBatchImportItems()
	{
		$items = $_POST['items'];
		$res = $this->model( 'item' )->batchAddItem( $items );
		if ( $res['code']===0 ) {
			EC::success(EC_OK, $res['data']);
		} 
	    EC::fail($res['code']);
	}

	/**
	 * @brief:  转换商品导入CSV条目的索引下标
	 * @param:  $importData
	 * @return:  
	 */
	private function convertKey( $importData )
	{
		if ( !$importData ) {
			return false;
		}
		$reData = [];
		foreach ( $importData as $k=>$val ) {
			$reData[$k]['category_name'] = $val[0]; // 分类名
			$reData[$k]['product_name'] = $val[1];	// 产品名
			$reData[$k]['material_name'] = $val[2]; // 材质名
			$reData[$k]['size_name'] = $val[3];		// 规格名
			$reData[$k]['factory_name'] = $val[4];  // 钢厂名
			$reData[$k]['city_name'] = $val[5];		// 城市名
			$reData[$k]['price'] = $val[6];			// 价格
			$reData[$k]['price_type_name'] = $val[7]; // 价格类型名称
			$reData[$k]['ton'] = $val[8]; // 单件种类
			$reData[$k]['count_type_name'] = $val[9]; // 计重类型名
			$reData[$k]['inventory'] = $val[10]; // 库存
			$reData[$k]['delivery_point'] = $val[11]; // 交割地
		}
		return $reData;
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
