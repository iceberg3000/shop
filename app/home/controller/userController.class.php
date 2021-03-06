<?php
//前台用户控制器
class userController extends commonController{
    public function __construct(){
        parent::__construct();
        $allow_action = array(//指定不需要检查登录的方法列表
            'login',
            'loginexec',
            'captcha',
            'register',
            'registerexec'
        );
        if(!IS_LOGIN && !in_array(ACTION,$allow_action)){
            $this->redirect('/shop/index.php?c=user&a=login');
        }
    }
    //显示用户注册页面
    public function registerAction(){
        require ACTION_VIEW;
    }

    //处理注册请求
    public function registerExecAction(){
        $this->_input('captcha');//检查验证码
        $username = $this->_input('username');      //获取用户名
        $password = $this->_input('password');      //获取密码
        $User = M('user');
        //验证用户名是否已经存在
        if($User->exists('username',$username)){
            $this->error('注册失败：用户名已经存在。');
        }
        //添加数据并取出新用户ID
        $salt = salt();//生成密钥
        $id = $User->data(array(
            'username'=>$username,
            'password'=>password($password,$salt),
            'salt'=>$salt,
        ))->add();
        //注册成功后自动登录
        $_SESSION['user'] = array('id'=>$id,'name'=>$username);
        $this->success('注册成功','/');
    }

    //接收表单并进行验证
    // private function _input($name){
    //     switch($name){
    //         case 'captcha'://验证码
    //             $value = I('captcha','post','string');
    //             $this->_checkCaptcha($value) || $this->error("登录失败，验证码输入有误");
    //             break;
    //         case 'username'://用户名
    //             $value = I('username','post','string');
    //             preg_match('/^[\w\x{4e00}-\x{9fa5}]{2,20}$/u',$value) || $this->error('用户名不合法（2~20位，汉字、英文、数字、下划线）');
    //             break;
    //         case 'password'://密码
    //             $value = I('password','post','string');
    //             preg_match('/^\w{6,20}$/',$value) || $this->error('密码不合法(6-20位，英文，数字，下划线)。');
    //             break;
    //     }
    //     return $value;
    // }

    //显示验证码
    public function captchaAction(){
        $Captcha = new captcha();
        $Captcha->create();
    }

    //检查验证码
    private function _checkCaptcha($captcha){
        $Captcha = new captcha();
        return $Captcha->verify($captcha);
    }

    //用户登录
    public function loginAction(){
        require ACTION_VIEW;
    }

    //处理登录请求
    public function loginExecAction(){
        $this->_input('captcha');   //检查验证码
        $username = $this->_input('username');      //获取用户名
        $password = $this->_input('password');      //获取密码
        //利用用户名和密码
        $data = D('user')->checkLogin($username,$password);
        $data || $this->error('登录失败，用户名或密码错误');
        //登录成功
        //$_SESSION['user'] = $data;      //将登录信息保存到session
        session('user',$data,'save'); //将登录信息保存到Session
        $this->success('登录成功');
    }

    //退出登录
    public function logoutAction(){
        unset($_SESSION['user']);
        empty($_SESSION) && session_destroy();
        $this->redirect('/');
    }

    //用户中心
    public function indexAction(){
        require ACTION_VIEW;
    }

    //查看收货地址
    public function addrAction(){
        $id = $this->user['id'];
        $data = D('user')->getAddr($id);
        require ACTION_VIEW;
    }

    //修改收货地址
    public function addrEditAction(){
        $this->addrAction();
    }
    public function addrEditExecAction(){
        //接收表单数据
        $fields = array('address','consignee','phone','email');
        $data = array();
        foreach($fields as $v){
            $data[$v] = $this->_input($v);
        }
        $data['id'] = $this->user['id'];
        M('user')->data($data)->save();
        $this->success("修改收货地址成功",'/shop/index.php?c=user&a=addr');
    }

    //接收表单并进行验证
	private function _input($name){
		switch($name){
			case 'captcha': //验证码
				$value = I('captcha','post','string');
				$this->_checkCaptcha($value) || $this->error('登录失败，验证码输入有误');
			break;
			case 'username'://用户名
				$value = I('username','post','string');
				preg_match('/^[\w\x{4e00}-\x{9fa5}]{2,20}$/u',$value) || $this->error('用户名不合法（2~20位，汉字、英文、数字、下划线）');
			break;
			case 'password'://密码
				$value = I('password','post','string');
				preg_match('/^\w{6,20}$/',$value) || $this->error('密码不合法（6-20位，英文、数字、下划线）。');
			break;
			case 'email': //邮箱地址
				$value = I('email','post','text');
				($value=="" || isset($value[30])) && $this->error('邮箱格式不正确（1-30个字符）。');
				preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',$value) || $this->error('邮箱格式不正确');
			break;
			case 'consignee': //收件人
				$value = I('consignee','post','text');
				($value=="" || isset($value[20])) && $this->error('收件人填写有误（1-20个字符）。');
			break;	
			case 'phone': //手机号码
				$value = I('phone','post','text');
				preg_match('/^1[0-9]{10}$/',$value) || $this->error('手机号码填写有误（11位数字）');
			break;	
			case 'address': //收货地址
				$value = I('address','post','text');
				($value=="" || isset($value[255])) && $this->error('收货地址填写有误（1-255个字符）。');
			break;
		}
		return $value;
	}
}