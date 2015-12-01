<?php

class PurchaseController extends BaseController
{
	public function handle( $params=[] )
	{
		if ( !$params ) {
			// 无参数，默认订单列表
			//$this-> orderList();
		}
	   	else switch( $params[0] )
		{
			case 'doAdd':  // 添加采购单
				$this-> doAdd();
				break;
				/*
			case 'add': 
				$this-> add();
				break;
			case 'getUser':
				$this-> ajaxGetUserInfoById();
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
				 */

			default:
				Log::error('page not found');
				EC::page_not_found();
				break;
		}
	}

	private function doAdd()
	{
		$order_id = $this->post( 'order_id', 0 );
		if ( !$order_id ) {
			return false;
		}
		$supplier = $this->post( 'supplier' );
		if ( !$supplier ) {
			return false;
		}
		$supplier_contact = $this->post( 'supplier_contact' );
		if ( !$supplier_contact ) {
			return false;
		}
		$supplier_tel = $this->post( 'supplier_tel' );
		if ( !$supplier_tel ) {
			return false;
		}

		$data = [
			'order_id'=>$order_id, 
			'supplier'=>$supplier, 
			'supplier_contact'=>$supplier_contact, 
			'supplier_tel'=>$supplier_tel 
		];

		$addRes = $this->model( 'purchase' )->add( $data );
		if ( $addRes['code']!==0 ) {
			//EC::fail( EC_ADD_FAL );
			$this-> jsonFail( 'add fail' );
		}
		//EC::success( EC_OK );
		$this-> jsonSuccess( 'add success' );
	}
	

}
