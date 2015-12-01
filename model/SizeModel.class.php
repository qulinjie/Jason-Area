<?php
/**
 * @file:  SizeModel.class.php
 * @brief:  产品规格尺寸表
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-12
 */


class SizeModel extends Model
{

	public function tableName()
	{
		return 'c_size';
	}

	public function getSize($params = array()){
		return $this->where($params)->from()->select();		
	}

	/**
	* 获取部分字段
	*/
	public function getSizeBasicInfo($size_id,$fields = array()){
		if(empty($fields))
			$data = $this->where('id=?', $size_id)->from()->select();
		else $data = $this->where('id=?', $size_id)->from(null, $fields)->select();
		if(empty($data)){
			Log::error('Size id not find ' . $size_id);
			return array();
		}
		return $data[0];
	}

	public function delSize($where){
		if(empty($where)){
			return false;
		}
		return $this->delete($where);
	}

	/**
	*更新分类
	*/
	public function updateSize($param,$where){
		if(empty($where)){
			Log::error('!!! upate all rows of Size');
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

	public function createSize($param = array()){
		if(! $this->insert(array(
				'id'		=>	$param['id'],
				'size' => $param['size'], 
				'material_id' => $param['material_id'], 
				'add_timestamp' => date('Y-m-d H:i:s',time())
		))){
			Log::error('create size error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return true;
	}


}
