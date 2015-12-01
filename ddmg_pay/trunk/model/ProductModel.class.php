<?php
/**
 * @file:  ProductModel.class.php
 * @brief:  产品，品名表
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-12
 */


class ProductModel extends Model
{

	public function tableName()
	{
		return 'c_product';
	}

	public function getProduct($params = array()){
		return $this->where($params)->from()->select();		
	}

	/**
	* 获取部分字段
	*/
	public function getProductBasicInfo($pro_id,$fields = array()){
		if(empty($fields))
			$data = $this->where('id=?', $pro_id)->from()->select();
		else $data = $this->where('id=?', $pro_id)->from(null, $fields)->select();
		if(empty($data)){
			Log::error('product id not find ' . $pro_id);
			return array();
		}
		return $data[0];
	}

	public function delProduct($where){
		if(empty($where)){
			return false;
		}
		return $this->delete($where);
	}

	/**
	*更新分类
	*/
	public function updateProduct($param,$where){
		if(empty($where)){
			Log::error('!!! upate all rows of product');
			return false;
		}
		if(empty($param)){
			return false;
		}
		return $this->update($param, $where);
	}

	/**
	*添加分类
	*/

	public function createProduct($param = array()){
		if(! $this->insert(array(
				'id'		=>	$param['id'],
				'short_name' => $param['short_name'], 
				'name'	=>	$param['name'],				
				'category_id'	=>	$param['category_id'],				
				'technic_id'	=>	$param['technic_id'],				
				'sort'	=>	$param['sort'],				
				'add_timestamp' => date('Y-m-d H:i:s',time())
		))){
			Log::error('create product error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return true;
	}


}
