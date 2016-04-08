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
            /* if( UserController::isSeller() ){
                $this->redirect($this->getBaseUrl().'tradeRecord/getIndexBill');
            } else {
                $this->redirect($this->getBaseUrl().'tradeRecord/getIndex');
            } */
            $this->redirect($this->getBaseUrl().'bcsCustomer/getInfo');
        }
    }
    
}
