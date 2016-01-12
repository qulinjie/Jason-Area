<?php


class BackendController extends Controller
{
    public function handle( $params= [])
    {
        switch($params[0]){
            case 'getIndex':
                $this->getIndex();
                break;
            case 'audit':
                $this->audit();
                break;
            default:
                Log::error('Backend method not exist params:'.$params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    private function getIndex()
    {
        $data = $this->searchList();
        $userListHtml = $this->render('user_list',['data' => $data],true);
        $userHtml     = $this->render('user',['user_list_html' => $userListHtml],true);
        $this->render('index',['page_type'=>'user','user_html'=>$userHtml]);
    }

    private function audit()
    {

    }

    private function searchList($isIndex = false) {
        $current_page = Request::post('page');
        $account      = Request::post('account');
        $pStatus      = Request::post('personStatus');
        $eStatus      = Request::post('enterpriseStatus');

        $user_model = $this->model('user');
        $params  = array();


        $data_cnt = $user_model->getCnt($params);
        if(EC_OK != $data_cnt['code']){
            Log::error("searchCnt failed . ");
            EC::fail($data_cnt['code']);
        }

        $cnt = $data_cnt['data'];
        $conf = $this->getConfig('conf');
        $page_cnt = $conf['page_count_default'];

        $total_page = ($cnt % $page_cnt) ? (integer)($cnt / $page_cnt) + 1 : $cnt / $page_cnt;

        if(!$current_page || 0 >= $current_page) {
            $current_page = 1;
        } if($current_page > $total_page) {
            $current_page = $total_page;
        }

        $params['current_page'] = $current_page;
        $params['page_count'] = $page_cnt;
        $data = $user_model->getList($params);
        if(EC_OK != $data['code']){
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }

        $data_list = $data['data'];
var_dump($data);exit;
        $entity_list_html = $this->render('bcsTrade_list', array('data_list' => $data_list, 'current_page' => $current_page, 'total_page' => $total_page), true);
        if($isIndex) {
            $view_html = $this->render('bcsTrade', array('entity_list_html' => $entity_list_html ), true);
            $this->render('index', array('page_type' => 'bcsTrade', 'bcsTrade_html' => $view_html));
        } else {
            EC::success(EC_OK, array('entity_list_html' => $entity_list_html));
        }
    }
}