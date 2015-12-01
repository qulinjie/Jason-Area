<?php
/**
 * @file:  MaterialModel.class.php
 * @brief:  产品材质表
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-12
 */


class MaterialModel extends Model
{

	public function tableName()
	{
		return 'c_material';
	}

	public function getMaterial($params = array()){
		return $this->where($params)->from()->select();		
	}

	/**
	* 获取部分字段
	*/
	public function getMaterialBasicInfo($mate_id,$fields = array()){
		if(empty($fields))
			$data = $this->where('id=?', $mate_id)->from()->select();
		else $data = $this->where('id=?', $mate_id)->from(null, $fields)->select();
		if(empty($data)){
			Log::error('material id not find ' . $mate_id);
			return array();
		}
		return $data[0];
	}

	public function delMaterial($where){
		if(empty($where)){
			return false;
		}
		return $this->delete($where);
	}

	/**
	*更新分类
	*/
	public function updateMaterial($param,$where){
		if(empty($where)){
			Log::error('!!! upate all rows of material');
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

	public function createMaterial($param = array()){
		if(! $this->insert(array(
				'id'		=>	$param['id'],
				'product_id' => $param['product_id'], 
				'name'	=>	$param['name'],				
				'add_timestamp' => date('Y-m-d H:i:s',time())
		))){
			Log::error('create material error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return true;
	}


}
