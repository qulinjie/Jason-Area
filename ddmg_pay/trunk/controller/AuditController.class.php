<?php

class AuditController extends BaseController
{
    public function handle( $params = [])
    {
        switch($params[0]){
            case 'index':
                $this->index();
                break;
            case 'getCertImg':
                $this->getCertImg();
                break;
            default:
                Log::error('AuditController method not exist params:'.$params[0]);
                EC::fail(EC_MTD_NON);
        }
    }

    private function index()
    {
        $data = $this->model('cert')->get();
        $session = $this->instance('session');
        $session->set('certInfo',$data['data'][0]);

        $audit_html = $this->render('audit',['data' => $data['data'][0]],true);
        $this->render('index',['page_type' => 'audit' , 'audit_html' => $audit_html]);
    }

    private function getCertImg()
    {
        $session  = $this->instance('session');
        $certInfo = $session->get('certInfo');

        if($certInfo){
            $flag = isset($_GET['flag']) ? true : false;
            $file = DOIT_ROOT;
            $file.= $flag ?  $certInfo['certificate_filepath']     : $certInfo['business_license_filepath'];
            $fileName  = $flag ? $certInfo['certificate_filename'] : $certInfo['business_license_filename'] ;
            $ext = explode('.',$fileName)[1];

            if(file_exists($file)){
                header('content-type:'.getimagesize($file)['mime']);
                echo file_get_contents($file);
            }
        }

    }
}