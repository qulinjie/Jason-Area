<?php
/**
 * @file:  TenderController.class.php
 * @brief:  卖家版，投标管理
 * @author:  Mark.zhangkui
 * @version:  0.1
 * @date:  2015-08-18
 */

class TenderController extends BaseController
{
	public function handle( $params=[] )
	{
		if ( !$params ) {
			$this->tenderList();
		}
	   	else switch( $params[0] )
		{
			case 'list':
				$this-> tenderList();
				break;
			case 'export':
			    $this-> exportTender();
			    break;
			default:
				Log::error('page not found');
				EC::page_not_found();
				break;
		}
	}
	
	protected function exportTender(){
	    $scope = $this->get( 'scope' );
	    $content = $this->get( 'content' );
	    
	    $excel_name = 'excel-投标列表-'.$scope;
	    $page = 1;
	    $page_count = 1;
	    
	    $excel = $this->instance('excel');
	    $tenderModel = $this->model( 'tender' );
	    $session =  Controller::instance('session');
	    $castTenderModel = $this->model( 'castTender' );
	    
	    $tender_list = array();
	    $params = array();
	    foreach (['content'] as $val){
	        if($$val) $params[$val] = $$val;
	    }
	    
	    //当前用户已投标的招标信息记录，需要排除。
	    $user_id = $session->get( 'loginUser' )['id'];
	    $tender_id_data = $castTenderModel->selectCastTenderActive(array('user_id'=>$user_id));
	    if(0 < count($tender_id_data)) {
	        $tender_ids = array();
	        foreach ( $tender_id_data as $key2=>$data2 ){
	            $tender_ids[] = $data2['tender_id'];
	        }
	        $params['tender_ids'] = $tender_ids;
	    }
	    
	    if('all' == $scope){
    	    $tender_list = $tenderModel->searchTender($params, null,null);
	    } else {
	        $config = Controller::getConfig( 'conf' );
	        $page_count = $config['page_count_default'];
	        
	        $total = $tenderModel->searchTenderCnt($params);
	        $page = ( int )$this->get( 'page', 1 );
	        $tender_list = $tenderModel->searchTender($params, $page, $page_count );
	    }
	    
	    $excel->setMenu(array('序号', '招标方', '招标信息', '交割地 ', '结束时间 ', '发布时间', '说明'));
	    $content = array();
	    foreach ( $tender_list as $key3=>$data3 ){
	        $content[$key3] = array(($key3+1+($page-1)*$page_count), $data3['user_name'], $data3['content'], $data3['delivery_point'], 
	            $data3['past_timestamp'], $data3['add_timestamp'], $data3['info']);
	    }
	    $excel->setData($content);
	    $excel->download($excel_name);
	}
	
	protected function tenderList(){
	    $content = $this->get( 'content' );
	    
	    $tenderModel = $this->model( 'tender' );
	    
	    $config = Controller::getConfig( 'conf' );
	    $page_count = $config['page_count_default'];
	    $session =  Controller::instance('session');
	    $castTenderModel = $this->model( 'castTender' );
	    
	    $params = array();
	    foreach (['content'] as $val){
	        if($$val) $params[$val] = $$val;
	    }
	    
	    //当前用户已投标的招标信息记录，需要排除。
	    $user_id = $session->get( 'loginUser' )['id'];
	    $tender_id_data = $castTenderModel->selectCastTenderActive(array('user_id'=>$user_id));
	    if(0 < count($tender_id_data)) {
    	    $tender_ids = array();
    	    foreach ( $tender_id_data as $key2=>$data2 ){
    	        $tender_ids[] = $data2['tender_id'];
    	    }
    	    $params['tender_ids'] = $tender_ids;
	    }
	    
	    // 总条数
	    $total = $tenderModel->searchTenderCnt($params);
	    
	    // 分页
	    $page = ( int )$this->get( 'page', 1 );
	    $pager_html = $this->getPageHtml( $page, $total, $page_count );
	    
	    // 获取数据
	    $tender_list = $tenderModel->searchTender($params, $page, $page_count );
	    
	    $data = [];
	    $data['queryString'] = $this->getQueryString();
	    $data['params'] = $params;
	    $data['tenderList'] = $tender_list;
	    $data['page'] = $page;
	    $data['numPerPage'] = $page_count;
	    $data['pager_html'] = $pager_html;
	    
	    $tenderList_html = $this->render('tenderList', $data, true);
	    $this->render( 'index', array( 'page_type'=>'tenderList', 'tenderList_html'=>$tenderList_html ) );
	}
	

	protected function init(  )
	{
	    PassportController::checklogin();
	}
}
