<?php

class IndexController extends Controller
{
    public function handle($params = [])
    {
        $this->display('home');
    }

    public function init()
    {
        if(UserController::isLogin()){
            $this->redirect($this->getBaseUrl().'authorizationCode/getIndex');
        }
    }
}
