<?php

class AddressModel extends Model {
	public function tableName(){
		return 'c_address';
	}
		
	public function getAddrBasicInfo($addrId){
		$data = $this->where('id=?', $addrId)->from()->select();
		if(empty($data)){
			Log::error('addr id not find ' . $addrId);
			return array();
		}
		return $data[0];
	}

	public function getAllAddrInfo($user_id){
		$data = $this->where('user_id=?', $user_id)->from()->select();
		if(empty($data)){
			Log::error('user id not find ' . $user_id);
			return array();
		}
		return $data;
	}

	/*插入地址*/
	public function createAddr($param = array()){
		if(! $this->insert(array(
				'id'		=>	$param['id'],
				'user_id'		=>	$param['user_id'],
				'name'	=>	$param['name'],
				'tel' => $param['tel'], 
				'province' => $param['province'],
				'city'	=>	$param['city'],
				'district'	=>	$param['district'],
				'address'	=>	$param['address'],
				'add_timestamp' => date('Y-m-d H:i:s',time())
		))){
			Log::error('create user error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
			return false;
		}
		return true;
	}	
	
	/*更新地址*/
	public function updateAddr($param,$where){
		if(empty($where)){
			Log::error('!!! upate all rows of user');
			return false;
		}
		if(empty($param)){
			return true;
		}
		return $this->update($param, $where);
	}	
	
}