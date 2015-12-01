<?php

class CastTenderModel extends Model {
    public function tableName(){
        return 'c_cast_tender';
    }
    
    //1-已投标，2-中标 3-已过期
    public static $_cast_tender_status_hasbid=1;
    public static $_cast_tender_status_winbid=2;
    public static $_cast_tender_status_expired=3;
    
    public function addCastTender($id, $user_id, $tender_id, $tender_user_id, $tender_user_name, $delivery_point, $content, $info, $status,$past_timestamp,$comment){
        if(! $this->insert(array(
            'id'		=>	$id,
            'user_id' => $user_id,
            'tender_id' => $tender_id,
            'tender_user_id'		=>	$tender_user_id,
            'tender_user_name'		=>	$tender_user_name,
            'delivery_point'	=>	$delivery_point,
            'content' => $content,
            'info' => $info,
            'status'	=>	CastTenderModel::$_cast_tender_status_hasbid,
            'comment'	=>	$comment,
            'past_timestamp'	=>	$past_timestamp,
            'add_timestamp' => date('Y-m-d H:i:s',time())
        ))){
            Log::error('create CastTender error: ' . $this->getErrorNo() . ' : ' . $this->getErrorInfo());
            return false;
        }
        return true;
    }
    
    public function searchCastTenderCnt($params = array()){
        $keys = array();
        $values = array();
        
        if($params['user_id']){
            $keys[] = 'user_id = ?';
            $values [] = $params['user_id'];
        }
        
        if($params['status'] && -1 != $params['status']){
            $keys[] = 'status = ?';
            $values [] = $params['status'];
        }
        
        if($params['content']){
            $keys[] = 'content like ?';
            $values [] = '%' . $params['content'] . '%';
        }
        
       return $this->count(null, 'id', $keys, $values);
    }
 
    public function searchCastTender($params = array(), $page = null, $count = null){
        $model = $this->from();
    
        if($params['user_id']){
            $model->where("user_id =" . $params['user_id'] );
        }
        
        if($params['status'] && -1 != $params['status']){
            $model->where("status =" . $params['status'] );
        }
        
        if($params['content']){
            $model->where("content like \"%" . $params['content'] . "%\"");
        }
        
        if($page && $count){
            $model->pageLimit($page, $count);
        }
        return $model->order('add_timestamp desc')->select();
    }
    
    public function selectCastTenderActive($params = array()){
        $model = $this->from(null ,array('tender_id'));
    
        if($params['user_id']){
            $model->where("user_id =" . $params['user_id'] );
        }
        $model->where("past_timestamp >= '" . date('Y-m-d H:i:s',strtotime(date('Y-m-d',time()).' 00:00:00')) . "'");
        
        return $model->select();
    }
    
}