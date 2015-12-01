<?php
/**
 * @file:  SellerModel.class.php
 * @brief:  经销商的>销售员表
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-12
 */


class SellerModel extends Model
{

	public function tableName()
	{
		return 'c_seller';
	}

	public function getUserInfoByTel( $tel )
	{
		$data = $this->where('tel=?', $tel)->from()->select();
		if(!$data){
			Log::notice('tel not find ' . $tel);
			return array();
		}
		return $data[0];
	}

	public function getSellerInfo($params = array(), $page = null, $count = null){
	    $model = $this->from();
	    $data = null;
	    if($page === null && $count === null) {
	        $data = $model->where($params)->order('add_timestamp desc')->select();
	    }else {
	        $data = $model->where($params)->order('add_timestamp desc')->pageLimit($page, $count)->select();
	    }
	    if(!$data){
	        Log::error('seller not find ');
	        return array();
	    }
	    return $data[0];
	}
	
	public function updateSeller($param, $where){
	    if(empty($where)){
	        Log::error('can not upate all rows of Size');
	        return false;
	    }
	    if(empty($param)){
	        return false;
	    }
	    return $this->update($param, $where);
	}
	
}
