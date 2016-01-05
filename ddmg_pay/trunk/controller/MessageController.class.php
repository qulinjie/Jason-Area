<?php


class MessageController extends BaseController
{

    public function handle($params = array())
    {
        switch ($params[0]) {
            case 'getIndex':
                $this->searchList();
                break;
            case 'searchList':
                $this->searchList(true);
                break;
            default:
                Log::error('page not found . ' . $params[0]);
                EC::fail(EC_MTD_NON);
                break;
        }
    }

    private function searchList($isSearchList = false)
    {
        if (!UserController::isLogin()) {
            Log::error('not login');
            EC::fail(EC_NOT_LOGIN);
        }

        $current_page = $this->post('page', 1);
        $data_cnt = $this->model('message')->getCnt();
        if (EC_OK !== $data_cnt['code']) {
            Log::error("getCnt failed . ");
            EC::fail($data_cnt['code']);
        }

        $cnt = $data_cnt['data'];

        $conf = $this->getConfig('conf');
        $page_cnt = $conf['page_count_default'];

        $total_page = ($cnt % $page_cnt) ? (integer)($cnt / $page_cnt) + 1 : $cnt / $page_cnt;

        if (!$current_page || 0 >= $current_page) {
            $current_page = 1;
        }
        if ($current_page > $total_page) {
            $current_page = $total_page;
        }

        $params['current_page'] = $current_page;
        $params['page_count'] = $page_cnt;
        $data = $this->model('message')->searchList($params);
        if (EC_OK !== $data['code']) {
            Log::error("searchList failed . ");
            EC::fail($data['code']);
        }

        $params = [
            'data_list' => $data['data'],
            'current_page' => $current_page,
            'total_page' => $total_page,
            'conf' => $this->getConfig('message')
        ];

        $message_html = $this->render('message_list', $params, true);
        $isSearchList && EC::success(EC_OK, array('message_list_html' => $message_html));
        $this->render('index', array('page_type' => 'message', 'message_html' => $message_html));
    }
}