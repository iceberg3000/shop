<?php
//前台公共控制器
class CommonController extends Controller{
	protected $user = array(); //保存用户信息
	protected $title = '传智商城';
	public function __construct() {
		parent::__construct();
		$this->_checkLogin();		//判断用户是否登录
	}
	private function _checkLogin(){
		if(session('user','','isset')){
			$this->user = session('user');
			define('IS_LOGIN',true);//设置常量保持判断结果
		}else{
			define('IS_LOGIN',false);
		}
	}
}