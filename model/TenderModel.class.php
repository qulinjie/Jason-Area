<?php

class TenderModel extends Model {
    public function tableName(){
        return 'c_tender';
    }
    
    public static $_tender_status_active=1;
    public static $_tender_status_finished=2;
    public static $_tender_status_expired=3;
    
    public function searchTenderCnt($params = array()){
        $keys = array();
        $values = array();
        
        $keys[] = 'status in ( ' . TenderModel::$_tender_status_active . ' )  and \'1\'=? ';
        $values[] = '1';
        
        $keys[] = 'past_timestamp >=?';
        $values[] = date('Y-m-d H:i:s',strtotime(date('Y-m-d',time()).' 00:00:00'));
        
        if($params['tender_ids'] && 0 < count($params['tender_ids'])){
            $ids = '-1';
            foreach ($params['tender_ids'] as $value) {
                $ids = $ids . ',' . $value;
            }
            $keys[] = 'id not in ( ' . $ids . ' )  and \'1\'=? ';
            $values[] = '1';
        }
        
        if($params['content']){
            $keys[] = 'content like ?';
            $values [] = '%' . $params['content'] . '%';
        }
        
       return $this->count(null, 'id', $keys, $values);
    }
 
    public function searchTender($params = array(), $page = null, $count = null){
        $model = $this->from();
    
        if($params['content']){
            $model->where("content like \"%" . $params['content'] . "%\"");
        }
        
        if($params['tender_ids'] && 0 < count($params['tender_ids'])){
            $ids = '-1';
            foreach ($params['tender_ids'] as $value) {
                $ids = $ids . ',' . $value;
            }
            $model->where("id not in (". $ids .")");
        }
        
        $model->where("past_timestamp >= '" . date('Y-m-d H:i:s',strtotime(date('Y-m-d',time()).' 00:00:00')) . "'");
        
        if($page && $count){
            $model->pageLimit($page, $count);
        }
        return $model->order('add_timestamp desc')->select();
    }
    
    public function getTender($params = array(), $page = null, $count = null){
        $model = $this->from();
        if($page === null && $count === null) {
            return $model->where($params)->order('add_timestamp desc')->select();
        }else {
            return $model->where($params)->order('add_timestamp desc')->pageLimit($page, $count)->select();
        }
    }
}