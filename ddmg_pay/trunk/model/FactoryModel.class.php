<?php
/**
 * @file:  FactoryModel.class.php
 * @brief:  厂家, 钢厂表，（宝钢、安钢，
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-12
 */

class FactoryModel extends Model
{

	public function tableName()
	{
		return 'c_factory';
	}


	public function getFactory($params = array()){
		return $this->where($params)->from()->select();		
	}

	/**
	* 获取部分字段
	*/
	public function getFactoryBasicInfo($fact_id,$fields = array()){
		if(empty($fields))
			$data = $this->where('id=?', $fact_id)->from()->select();
		else $data = $this->where('id=?', $fact_id)->from(null, $fields)->select();
		if(empty($data)){
			Log::error('item id not find ' . $fact_id);
			return array();
		}
		return $data[0];
	}

	public function delFactory($where){

		if(empty($where)){
			return false;
		}
		return $this->delete($where);
	}

	/**
	*更新分类
	*/
	public function updateFactory($param,$where){
		if(empty($where)){
			Log::error('!!! upate all rows of Size');
			return false;
		}
		if(empty($param)){
			return false;
		}
		return $this->update($param, $where);
	}
	
	/*插入商品*/
	public function createFactory($param = array()){
		//print_r($param);exit;
		if(! $this->insert(array(
				'id'		=>	$param['id'],
				'short_name'		=>	$param['short_name'],
				'name'	=>	$param['name'],
				'full_name' => $param['full_name'],
				'add_timestamp' => date('Y-m-d H:i:s',time())
		))){
			Log::error('create factory error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return true;
	}

}
