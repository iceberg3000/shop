<?php
//后台管理员登录
class loginController extends controller{
    //显示登录页面
    public function indexAction(){
        require ACTION_VIEW;
    }
    //显示验证码
    public function captchaAction(){
        $Captcha = new captcha();
        $Captcha->create();
    }

    //退出登录
    public function logoutAction(){
        unset($_SESSION['admin']);
        empty($_SESSION) &&　session_destroy();
        $this->redirect('/?p=admin&c=login');
    }
}