<?php

class OrderModel extends CurlModel 
{
	public function getCount( $condition )
	{
		if ( !$condition ) {
			return false;
		}
		$interface = 'sellerOrder/getCount';
		$data['condition'] = $condition;
		return $this-> sendRequest( $interface, $data );
	}

	public function getOrderList( $condition )
	{
		if ( !$condition ) {
			return false;
		}
		$interface = 'sellerOrder/getList';
		$data['condition'] = $condition;
		return $this-> sendRequest( $interface, $data );
	}

	public function getOrderDetailById( $id )
	{
		if ( !$id ) {
			return false;
		}
		$interface = 'sellerOrder/order';
		$data['id'] = $id;
		return $this-> sendRequest( $interface, $data );
	}

	public function modifyOrder( $data )
	{
		if ( !$data ) {
			return false;
		}
		$interface = 'sellerOrder/modify';
		return $this-> sendRequest( $interface, $data );
	}

	public function audit( $data )
	{
		if ( !$data ) {
			return false;
		}
		$interface = 'sellerOrder/audit';
		return $this-> sendRequest( $interface, $data );
	}

	public function addNewOrder( $data )
	{
		if ( !$data ) {
			return false;
		}
		$interface = 'sellerOrder/add';
		return $this-> sendRequest( $interface, $data );
	}

	public function unlinePayComplete( $data )
	{
		if ( !$data ) {
			return false;
		}
		$interface = 'sellerOrder/unlinePay';
		return $this-> sendRequest( $interface, $data );
	}

	public function getPayBundle( $id )
	{
		if ( !$id ) {
			return false;
		}
		$data['id'] = $id;
		$interface = 'sellerOrder/getPayBundle';
		return $this-> sendRequest( $interface, $data );
	}

	public function delivery( $data )
	{
		if ( !$data ) {
			return false;
		}
		//$data['data'] = $data;
		$interface = 'sellerOrder/delivery';
		return $this-> sendRequest( $interface, $data );
	}

	public function deliveryInfo( $id )
	{
		if ( !$id ) {
			return false;
		}
		$data['id'] = $id;
		$interface = 'sellerOrder/deliveryInfo';
		return $this-> sendRequest( $interface, $data );
	}

	public function cancel( $id )
	{
		if ( !$id ) {
			return false;
		}
		$data['id'] = $id;
		$interface = 'sellerOrder/cancel';
		return $this-> sendRequest( $interface, $data );
	}

	public function done( $id )
	{
		if ( !$id ) {
			return false;
		}
		$data['id'] = $id;
		$interface = 'sellerOrder/done';
		return $this-> sendRequest( $interface, $data );
	}


	public function createOrder( $params=[] )
	{
        return self::sendRequestErp('order/create', $params);

        $data = self::sendRequestErp('order/create', $params);
        return json_decode($data,true);
    }

}
