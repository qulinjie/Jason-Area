<?php

class OrderItemModel extends Model 
{
	public function tableName()
	{
		return 'c_order_item';
	}
		
	public function getOrderItemBasicInfo( $order_item_id )
	{
		if ( !$order_item_id ) {
			Log::error('order_item id not find ' . $order_item_id);
			return false;
		}
		$data = $this->where('id=?', $order_item_id)->from()->select();
		if(empty($data)){
			Log::error('order_item id not find ' . $order_item_id);
			return array();
		}
		return $data[0];
	}

	public function getOrderItemInfo( $where = array() )
	{
		$data = $this->where($where)->from()->select();	
		return $data;
	}
	
	/*插入订单*/
	public function createOrderItem( $param = array() )
	{
		if(! $this->insert(array(
				'id'		=>	$param['id'],
				'order_id'		=>	$param['order_id'],
				'merchant_id'		=>	$param['merchant_id'],
				'factory_id'	=>	$param['factory_id'],
				'product_id'	=>	$param['product_id'],
				'material_id'	=>	$param['material_id'],
				'size_id'	=>	$param['size_id'],
				'seller_id' => $param['seller_id'], 
				'delivery_point' => $param['delivery_point'],
				'price'	=>	$param['price'],
				'ton'	=>	$param['ton'],
				'price_per_ton'	=>	$param['price_per_ton'],

				'quantity'	=>	$param['quantity'],
				'add_timestamp' => date('Y-m-d H:i:s',time())
		))){
			Log::error('create order_item error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return true;
	}		

	/**
	 * @brief:  修改订单商品
	 * @param:  $data
	 * @param:  $where
	 * @param:  $param
	 * @last-change: 2015/9/6 1:55
	 * @return:  
	 */
	public function updateOrderItem( $data, $where, $params=null )
	{
		if (!$data || !$where ) {
			Log::error('!!! upate all rows of order_item');
			return false;
		}
		return $this->update($data, $where, $params);
	}	


	/**
	 * @brief:  根据订单ID，获取订单商品详情
	 * @param:  $orderIds
	 * @return:  
	 */
	public function getOrderItemByOrderId( $orderIds )
	{
		if ( !$orderIds ) {
			return false;
		}
		if ( !is_array( $orderIds ) ) {
			$orderIds = [$orderIds];
		}
		$this-> from();
		$this-> where( ['order_id'=>$orderIds, 'is_delete'=>0] );
		$items = $this-> select();
		return $this->complete( $items ); // 补全数据
	}

	/**
	 * @brief:   根据订单商品ID，获取订单商品详情
	 * @param:  $ids
	 * @return:  
	 */
	public function getOrderItemById( $ids )
	{
		if ( !$ids ) {
			return false;
		}
		if ( !is_array( $ids ) ) {
			$ids = [$ids];
		}
		$this-> from();
		$this-> where( ['id'=>$ids, 'is_delete'=>0] );
		$items = $this-> select();
		return $this->complete( $items ); // 补全数据
	}

	/**
	 * @brief:  补全商品的其他关联数据
	 * @param:  $items
	 * @return:  
	 */
	private function complete( $items )
	{
		if ( !$items ) return false;

		$merchantIds = $factoryIds = $productIds = $materialIds = $sizeIds = $sellerIds = [];
		foreach ( $items as $item ) {
			$merchantIds[] = $item['merchant_id'];
			$factoryIds[] = $item['factory_id'];
			$productIds[] = $item['product_id'];
			$materialIds[] = $item['material_id'];
			$sizeIds[] = $item['size_id'];
			$sellerIds[] = $item['seller_id'];
			$cityIds[] = $item['city_id'];
			$categoryIds[] = $item['category_id'];
		}

		if ( count($cityIds) > 0 ) {
			$cityInfos = Controller::model( 'city' )->find($cityIds);
			$cityInfos = $cityInfos ? $cityInfos : [];
			$cityIdMap = [];
			foreach ( $cityInfos as $val ) {
				$cityIdMap[$val['id']] = $val;
			}
		}

		if ( count($merchantIds) > 0 ) {
			$merchantInfos = Controller::model( 'merchant' )->find($merchantIds);
			$merchantInfos = $merchantInfos ? $merchantInfos : [];
			$merchantIdMap = [];
			foreach ( $merchantInfos as $val ) {
				$merchantIdMap[$val['id']] = $val;
			}
		}
		if ( count($factoryIds) > 0 ) {
			$factoryInfos = Controller::model( 'factory' )->find($factoryIds);
			$factoryInfos = $factoryInfos ? $factoryInfos : [];
			$factoryIdMap = [];
			foreach ( $factoryInfos as $val ) {
				$factoryIdMap[$val['id']] = $val;
			}
		}
		if ( count($productIds) > 0 ) {
			$productInfos = Controller::model( 'product' )->find($productIds);
			$productInfos = $productInfos ? $productInfos : [];
			$productIdMap = [];
			//$categoryIds = [];
			$technicIds = [];
			
			foreach ( $productInfos as $val ) {
				$productIdMap[$val['id']] = $val;
				//$categoryIds[$val['category_id']] = 1;
				$technicIds[$val['technic_id']] = 1;
			}
			/*
			if ( $categoryIds ) {
				$categoryIds = array_keys( $categoryIds ); // 从品名里拿分类ID
			}
			 */
			if ( $technicIds ) {
				$technicIds = array_keys( $technicIds ); // 从品名里拿工艺ID
			}
		}

		if ( count($categoryIds) > 0 ) {
			$categoryInfos = Controller::model( 'category' )->find($categoryIds);
			$categoryInfos = $categoryInfos ? $categoryInfos : [];
			$categoryIdMap = [];
			foreach ( $categoryInfos as $val ) {
				$categoryIdMap[$val['id']] = $val;
			}
		}
		if ( count($technicIds) > 0 ) {
			$technicInfos = Controller::model( 'technic' )->find($technicIds);
			$technicInfos = $technicInfos ? $technicInfos : [];
			$technicIdMap = [];
			foreach ( $technicInfos as $val ) {
				$technicIdMap[$val['id']] = $val;
			}
		}
		if ( count($materialIds) > 0 ) {
			$materialInfos = Controller::model( 'material' )->find($materialIds);
			$materialInfos = $materialInfos ? $materialInfos : [];
			$materialIdMap = [];
			foreach ( $materialInfos as $val ) {
				$materialIdMap[$val['id']] = $val;
			}
		}
		if ( count($sizeIds) > 0 ) {
			$sizeInfos = Controller::model( 'size' )->find($sizeIds);
			$sizeInfos = $sizeInfos ? $sizeInfos : [];
			$sizeIdMap = [];
			foreach ( $sizeInfos as $val ) {
				$sizeIdMap[$val['id']] = $val;
			}
		}
		if ( count($sellerIds) > 0 ) {
			$sellerInfos = Controller::model( 'seller' )->find($sellerIds);
			$sellerInfos = $sellerInfos ? $sellerInfos : [];
			$sellerIdMap = [];
			foreach ( $sellerInfos as $val ) {
				$sellerIdMap[$val['id']] = $val;
			}
		}

		foreach ( $items as $key => $val ) {
			$items[$key]['merchant_name'] = $merchantIdMap[$val['merchant_id']]['name'];
			$items[$key]['factory_name'] = $factoryIdMap[$val['factory_id']]['name'];
			$items[$key]['product_name'] = $productIdMap[$val['product_id']]['name'];
			$items[$key]['material_name'] = $materialIdMap[$val['material_id']]['name'];
			$items[$key]['size_name'] = $sizeIdMap[$val['size_id']]['size'];
			$items[$key]['seller_name'] = $sellerIdMap[$val['seller_id']]['name'];
			$items[$key]['city_name'] = $cityIdMap[$val['city_id']]['name'];
			$items[$key]['category_name'] = $categoryIdMap[$val['category_id']]['name'];

			//$items[$key]['category_id'] = $productIdMap[$val['product_id']]['category_id'];
			//$items[$key]['category_name'] = $categoryIdMap[$productIdMap[$val['product_id']]['category_id']]['name'];
			$items[$key]['technic_id'] = $productIdMap[$val['product_id']]['technic_id'];
			$items[$key]['technic_name'] = $technicIdMap[$productIdMap[$val['product_id']]['technic_id']]['name'];
		}
		return $items;
	}

		
}
