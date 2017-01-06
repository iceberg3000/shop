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

    //验证登录表单
    public function loginExecAction(){
        //echo $_POST['captcha'];
        if(!$this->_checkCaptcha($_POST['captcha'])){
            $this->error('登录失败，验证码输入有误');
        }

        //判断用户名密码
        $data = D('admin')->checkLogin($_POST['username'],$_POST['password']);
        $data || $this->error('登录失败，用户名或密码错误');

        //登录成功
        $_SESSION['admin'] = $data;
        $this->success('','/shop/index.php?p=admin');
    }

    //判断验证码
    private function _checkCaptcha($captcha){
        //echo $Captcha;
        $Captcha = new captcha();
        return $Captcha->verify($captcha);
    }
}