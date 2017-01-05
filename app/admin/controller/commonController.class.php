<?php
//后台公共控制器
class commonController extends controller{
    protected $user = array();              //保存用户信息
    public function __construct(){
        parent::__construct();
        $this->_checkLogin();               //检查用户是否登录
    }

    private function _checkLogin(){
        if(isset($_SESSION['admin'])){
            $this->user = $_SESSION['admin'];
        }else{
            $this->redirect('/shop/index.php?p=admin&c=login');
        }
    }
}