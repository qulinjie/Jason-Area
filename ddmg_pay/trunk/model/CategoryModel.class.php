<?php
/**
 * @file:  CategoryModel.class.php
 * @brief: 商品分类：板材，建材，单表非无限分类
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-12
 */


class CategoryModel extends Model
{

	public function tableName()
	{
		return 'c_category';
	}

	public function getCategory($params = array()){
		return $this->where($params)->from()->select();		
	}

	/**
	* 获取部分字段
	*/
	public function getCategoryBasicInfo($cate_id,$fields = array()){
		if(empty($fields))
			$data = $this->where('id=?', $cate_id)->from()->select();
		else $data = $this->where('id=?', $cate_id)->from(null, $fields)->select();
		if(empty($data)){
			Log::error('category id not find ' . $cate_id);
			return array();
		}
		return $data[0];
	}

	public function delCategory($where){
		if(empty($where)){
			return false;
		}
		return $this->delete($where);
	}

	/**
	*更新分类
	*/
	public function updateCategory($param,$where){
		if(empty($where)){
			Log::error('!!! upate all rows of category');
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

	public function createCategory($param = array()){
		if(! $this->insert(array(
				'id'		=>	$param['id'],
				'short_name' => $param['short_name'], 
				'name'	=>	$param['name'],				
				'add_timestamp' => date('Y-m-d H:i:s',time())
		))){
			Log::error('create category error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return true;
	}

}
