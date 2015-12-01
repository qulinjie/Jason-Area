<?php
/**
 * @file:  ItemModel.class.php
 * @brief:  商品表
 * @author:  
 * @version:  0.1
 * @date:  2015-08-12
 */


class ItemModel extends CurlModel
{
	public function update( $data )
	{
		if ( !$data['id'] ) {
			return false;
		}
		$interface = 'sellerItem/update';
		return $this-> sendRequest( $interface, $data );
	}

	public function delete( $id )
	{
		if ( !$id ) {
			return false;
		}
		$interface = 'sellerItem/delete';
		$data['id'] = $id;
		return $this-> sendRequest( $interface, $data );
	}

	public function getItemById( $id )
	{
		if ( !$id ) {
			return false;
		}
		$interface = 'sellerItem/item';
		$data['id'] = $id;
		return $this-> sendRequest( $interface, $data );
	}

	public function getCount( $condition )
	{
		if ( !$condition ) {
			return false;
		}
		$interface = 'sellerItem/countItem';
		$data['condition'] = $condition;
		return $this-> sendRequest( $interface, $data );
	}
	//获取交割地
	public function getDAddr()
	{
		$interface = 'warehouse/getList';
		return $this-> sendRequest( $interface, [] );
	}

	public function getList( $conditionArr )
	{
		if ( !$conditionArr ) {
			return false;
		}
		/*
		$data['category_id']	=	$conditionArr['category_id'];
		$data['material_id']	=	$conditionArr['material_id'];
		$data['merchant_id']	= 	$conditionArr['merchant_id'];
		$data['product_id']		= 	$conditionArr['product_id'];
		$data['factory_id']		= 	$conditionArr['factory_id'];
		$data['size_id']		= 	$conditionArr['size_id'];
		$data['count']			= 	$conditionArr['count'];
		$data['page']			= 	$conditionArr['page'];
		 */
		//$interface = 'item/get_list';
		$interface = 'sellerItem/list';
		return $this-> sendRequest( $interface, $conditionArr );
		return $this-> sendRequest( $interface, $data );

	}

	public function toggle( $id )
	{
		if ( !$id ) {
			return false;
		}
		$interface = 'sellerItem/toggle';
		return $this-> sendRequest( $interface, ['id'=>$id] );
	}

	public function batchOnsale( $idArr )
	{
		if ( !$idArr ) {
			return false;
		}
		$interface = 'sellerItem/batchOnsale';
		return $this-> sendRequest( $interface, ['idArr'=>$idArr] );
	}
	public function batchUnsale( $idArr )
	{
		if ( !$idArr ) {
			return false;
		}
		$interface = 'sellerItem/batchUnsale';
		return $this-> sendRequest( $interface, ['idArr'=>$idArr] );
	}

	//case 'city':// 根据材质ID，获取规格
	//$this-> getCityList( $req_data );
	public function getCityList()
	{
		$interface = 'sellerItem/city';
		return $this-> sendRequest( $interface );
	}
	
	//case 'factory':// 根据材质ID，获取规格
	//$this-> getFactoryList( $req_data );
	public function getFactoryList()
	{
		$interface = 'sellerItem/factory';
		return $this-> sendRequest( $interface );
	}

	public function getCategoryList()
	{
		$interface = 'sellerItem/category';
		return $this-> sendRequest( $interface );
	}

	public function getProductList( $category_id )
	{
		if ( !$category_id ) {
			return false;
		}
		$interface = 'sellerItem/product';
		$data['category_id'] = $category_id;
		return $this-> sendRequest( $interface, $data );
	}

	//case 'material':// 根据品名ID，获取材质
	//$this-> getMaterialByProductId( $req_data );
	public function getMaterialList( $product_id )
	{
		if ( !$product_id ) {
			return false;
		}
		$interface = 'sellerItem/material';
		$data['product_id'] = $product_id;
		return $this-> sendRequest( $interface, $data );
	}

	public function getSizeList( $material_id )
	{
		if ( !$material_id ) {
			return false;
		}
		$interface = 'sellerItem/size';
		$data['material_id'] = $material_id;
		return $this-> sendRequest( $interface, $data );
	}

	public function addItem( $data )
	{
		if ( !$data ) {
			return false;
		}
		$interface = 'sellerItem/doAdd';
		return $this-> sendRequest( $interface, $data );
	}

	public function batchAddItem( $data )
	{
		if ( !$data ) {
			return false;
		}
		$post['data'] = $data;
		$interface = 'sellerItem/batchAdd';
		return $this-> sendRequest( $interface, $post );
	}

	public function addErpItem( $data )
	{
		if ( !$data ) {
			return false;
		}
		//$post['data'] = $data;
		$interface = 'sellerItem/addErpItem';
		return $this-> sendRequest( $interface, $data );
		//return $this-> sendRequest( $interface, $post );
	}


}
