<?php

class IndexController extends Controller
{
    public function handle($params = [])
    {
        $this->display('home');
    }

    public function init()
    {
        if(AdminController::isAdmin()){
            Log::notice("============================IndexController init =====================================isAdmin=============================");
        } else if(UserController::isLogin()){
            $this->redirect($this->getBaseUrl().'tradeRecord/getIndex');
        }
    }
    
}
