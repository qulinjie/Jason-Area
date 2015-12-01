<?php
/**
 * @file:  TechnicModel.class.php
 * @brief:  产品工艺表
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-12
 */


class TechnicModel extends Model
{

	public function tableName()
	{
		return 'c_technic';
	}

	public function getTechnic($params = array()){
		return $this->where($params)->from()->select();		
	}

	/**
	* 获取部分字段
	*/
	public function getTechnicBasicInfo($tec_id,$fields = array()){
		if(empty($fields))
			$data = $this->where('id=?', $tec_id)->from()->select();
		else $data = $this->where('id=?', $tec_id)->from(null, $fields)->select();
		if(empty($data)){
			Log::error('Technic id not find ' . $tec_id);
			return array();
		}
		return $data[0];
	}

	public function delTechnic($where){
		if(empty($where)){
			return false;
		}
		return $this->delete($where);
	}

	/**
	*更新分类
	*/
	public function updateTechnic($param,$where){
		if(empty($where)){
			Log::error('!!! upate all rows of Technic');
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

	public function createTechnic($param = array()){
		if(! $this->insert(array(
				'id'		=>	$param['id'],
				'short_name' => $param['short_name'], 
				'name'	=>	$param['name'],
				'add_timestamp' => date('Y-m-d H:i:s',time())
		))){
			Log::error('create technic error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return true;
	}

}
