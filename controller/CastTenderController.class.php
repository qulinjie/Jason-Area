<?php
/**
 * @file:  TenderController.class.php
 * @brief:  卖家版，投标管理
 * @author:  Mark.zhangkui
 * @version:  0.1
 * @date:  2015-08-18
 */

class CastTenderController extends BaseController
{
	public function handle( $params=[] )
	{
		if ( !$params ) {
			$this->castTenderList();
		}
	   	else switch( $params[0] )
		{
			case 'list':
				$this-> castTenderList();
				break;
			case 'add':
			    $this-> addCastTender();
			    break;
		    case 'export':
		        $this-> exportCastTender();
		        break;
			default:
				Log::error('page not found');
				EC::page_not_found();
				break;
		}
	}
	
	protected function exportCastTender(){
	    $scope = $this->get( 'scope' );
	    $content = $this->get( 'content' );
	    $status = $this->get( 'status' );
	    
	    $excel_name = 'excel-投标列表-'.$scope;
	    $page = 1;
	    $page_count = 1;
	     
	    $excel = $this->instance('excel');
	    $castTenderModel = $this->model( 'castTender' );
	    $userModel = $this->model( 'user' );
	    
	    $session =  Controller::instance('session');
	    $user_id = $session->get( 'loginUser' )['id'];
	    
	    $castTender_list = array();
	    $params = array();
	    foreach (['content','user_id','status'] as $val){
	        if($$val) $params[$val] = $$val;
	    }
	    
	    if('all' == $scope){
	        $castTender_list = $castTenderModel->searchCastTender($params, null,null);
	    } else {
	        $config = Controller::getConfig( 'conf' );
	        $page_count = $config['page_count_default'];
	         
	        $total = $castTenderModel->searchCastTenderCnt($params);
	        $page = ( int )$this->get( 'page', 1 );
	        $castTender_list = $castTenderModel->searchCastTender($params, $page, $page_count );
	    }
	    
	    
	    $excel->setMenu(array('序号', '招标方', '招标方', '招标信息', '交割地 ', '结束时间 ', '发布时间', '说明', '投标状态', '投标信息'));
	    $content = array();
	    foreach ( $castTender_list as $key3=>$data3 ){
	        if(CastTenderModel::$_cast_tender_status_hasbid == $data3['status']) {
	            $status_str = '已投标';
	        } else if(CastTenderModel::$_cast_tender_status_winbid == $data3['status']) {
	            $status_str = '中标';
	            $user_tmp = $userModel->getUserBasicInfo($data3['tender_user_id'],'tel');
	            $tender_tel = $user_tmp['tel'];
	        } else if(CastTenderModel::$_cast_tender_status_expired == $data3['status']) {
	            $status_str = '已过期';
	        }
	        $content[$key3] = array(($key3+1+($page-1)*$page_count), $data3['tender_user_name'], $tender_tel, $data3['content'], $data3['delivery_point'],
	            $data3['past_timestamp'], $data3['add_timestamp'], $data3['info'], $status_str, $data3['comment']);
	    }
	    $excel->setData($content);
	    $excel->download($excel_name);
	}
	
	protected function addCastTender(){
	    $tender_id = $this->post( 'id' );
	    $comment = $this->post( 'comment' );
	    
	    $tenderModel = $this->model( 'tender' );
	    $data_tender = $tenderModel->getTender(array('id' => $tender_id));
	    if(empty($data_tender)){
	        Log::error('tender is not exist, ' . $tender_id);
	        EC::fail(EC_PAR_ERR);
	    }
	    $data_tender = $data_tender[0];
	    
	    $tender_user_id = $data_tender['user_id'];
	    $tender_user_name = $data_tender['user_name'];
	    $delivery_point = $data_tender['delivery_point'];
	    $content = $data_tender['content'];
	    $info = $data_tender['info'];
	    $status = $data_tender['status'];
	    $past_timestamp = $data_tender['past_timestamp'];
	    
	    $id_model = $this->model('id');
	    $id = $id_model->getCastTenderId();
	    
	    $session =  Controller::instance('session');
	    $user_id = $session->get( 'loginUser' )['id'];
	    
	    $castTenderModel = $this->model( 'castTender' );
	    $ret = $castTenderModel->addCastTender($id, $user_id, $tender_id, $tender_user_id, $tender_user_name, $delivery_point, $content, $info, $status,$past_timestamp,$comment);
	    if(false === $ret){
	        Log::error('add CastTender Fail!');
	        EC::fail(EC_ADD_REC);
	    }
	    EC::success(EC_OK);
	}
	
	protected function castTenderList(){
	    
	    $content = $this->get( 'content' );
	    $status = $this->get( 'status' );
	    
	    $castTenderModel = $this->model( 'castTender' );
	    $userModel = $this->model( 'user' );
	    
	    $config = Controller::getConfig( 'conf' );
	    $page_count = $config['page_count_default'];
	    
	    $session =  Controller::instance('session');
	    $user_id = $session->get( 'loginUser' )['id'];
	    
	    $params = array();
	    foreach (['content','user_id','status'] as $val){
	        if($$val) $params[$val] = $$val;
	    }
	    
	    // 总条数
	    $total = $castTenderModel->searchCastTenderCnt($params);
	    
	    // 分页
	    $page = ( int )$this->get( 'page', 1 );
	    $pager_html = $this->getPageHtml( $page, $total, $page_count );
	    
	    // 获取数据
	    $castTender_list = $castTenderModel->searchCastTender($params, $page, $page_count );
	    
	    if(0 < count($castTender_list)){
	        foreach ( $castTender_list as $key1=>$data1 ){
	            if(CastTenderModel::$_cast_tender_status_winbid == $data1['status']){
    	            $user_tmp = $userModel->getUserBasicInfo($data1['tender_user_id'],'tel');
    	            $castTender_list[$key1]['tender_tel'] = $user_tmp['tel'];
	            } else {
	                $castTender_list[$key1]['tender_tel'] = '';
	            }
	        }
	    }
	    
	    $data = [];
	    $data['queryString'] = $this->getQueryString();
	    $data['params'] = $params;
	    $data['castTenderList'] = $castTender_list;
	    $data['page'] = $page;
	    $data['numPerPage'] = $page_count;
	    $data['pager_html'] = $pager_html;
	    
	    $castTenderList_html = $this->render('castTenderList', $data, true);
	    $this->render( 'index', array( 'page_type'=>'tenderList', 'tenderList_html'=>$castTenderList_html ) );
	}
	

	protected function init(  )
	{
	    PassportController::checklogin();
	}
}
