<?php

class PurchaseModel extends CurlModel 
{
	public function add( $data )
	{
		if ( !$data ) {
			return false;
		}
		$interface = 'purchaseOrder/add';
		return $this-> sendRequest( $interface, $data );
	}


//============================================ old
	public function getCount( $condition )
	{
		if ( !$condition ) {
			return false;
		}
		$interface = 'purchase/getCount';
		$data['condition'] = $condition;
		return $this-> sendRequest( $interface, $data );
	}

	public function getOrderList( $condition )
	{
		if ( !$condition ) {
			return false;
		}
		$interface = 'purchase/getList';
		$data['condition'] = $condition;
		return $this-> sendRequest( $interface, $data );
	}

	public function getOrderDetailById( $id )
	{
		if ( !$id ) {
			return false;
		}
		$interface = 'purchase/order';
		$data['id'] = $id;
		return $this-> sendRequest( $interface, $data );
	}

	public function modifyOrder( $data )
	{
		if ( !$data ) {
			return false;
		}
		$interface = 'purchase/modify';
		return $this-> sendRequest( $interface, $data );
	}

	public function audit( $data )
	{
		if ( !$data ) {
			return false;
		}
		$interface = 'purchase/audit';
		return $this-> sendRequest( $interface, $data );
	}

}
