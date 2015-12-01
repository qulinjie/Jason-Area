<?php
/**
 * @file:  PriceModel.class.php
 * @brief:  产品价格表
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-12
 */


class PriceModel extends Model
{

	public function tableName()
	{
		return 'c_price';
	}

	/**
	 * @brief:  create data for c_price table
	 * @param:  $data
	 * @return:  
	 */
	public function c( $data, $returnInsertId=false )
	{
		if ( !$data ) {
			Log::error('create c_price no data: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		if ( $returnInsertId!==false ) {
			$returnInsertId = ( bool )$returnInsertId;
		}

		$insertPriceLog = $this->insert( $data, $returnInsertId );
		if ( !$insertPriceLog ) {
			Log::error('create c_price error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return $returnInsertId ? $insertPriceLog : true;
	}

	public function u( $data, $where, $params=null )
	{
		if ( !$data || !$where  ) {
			return false;
		}
		return $this->update( $data, $where, $params );
	}

	public function r( $whereK, $whereV, $page, $numPerPage )
	{
		$this-> from();
		$this-> where( $whereK, $whereV );
		$this-> order( 'add_timestamp DESC' );
		$this-> pageLimit( $page, $numPerPage ); // $page 当前页，$count 每页显示
		$price_history = $this-> select();
		return $price_history;
		return $this->complete( $items );
	}

	public function d( $where, $params=null )
	{
		if ( !$where ) {
			return false;
		}
		return $this-> delete( $where, $params );
	}


}
