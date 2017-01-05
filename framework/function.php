<?php
//遇到致命错误时，输出错误信息并停止支行
function E($msg){
        header('content-type:text/html;charset=utf-8');
        die('<pre>'.htmlspecialchars($msg).'</pre>');
}
//配置文件操作
function C($name,$value=null){
    static $config = null;//保存项目中的设置
    if(!$config){//函数首次被调用时载入配置文件
        $config = require COMMON_PATH . 'config.php';
    }
    if($value==null){//省略value参数表示获取配置项，否则修改配置项
        return isset($config[$name]) ? $config[$name] : '';
    }else{
        $config[$name] = $value;
    }
}

//实例化模型
function D($name){
    static $Model = array();
    $name = strtolower($name);              //统一转换为小写
    if(!isset($Model[$name])){              //单例模式
        $model_name = $name.'Model';
        $Model[$name] = new $model_name($name);
    }
    return $Model[$name];
}

//实例化基类模型
function M($name=''){
    static $Model = array();
    $name = strtolower($name);              //统一转换为小写
    if(!isset($Model[$name])){              //单例模式
        $Model[$name] = new Model($name);
    }
    return $Model[$name];
}

//Session读写
function session($name,$value='',$type='get'){
	$prefix = C('SESSION_PREFIX');
	isset($_SESSION[$prefix]) || $_SESSION[$prefix] = array();
	switch($type){
		case 'get':
			return isset($_SESSION[$prefix][$name]) ? $_SESSION[$prefix][$name] : '';
		case 'isset':
			return isset($_SESSION[$prefix][$name]);
		case 'save':
			$_SESSION[$prefix][$name] = $value;
		break;
		case 'unset':
			unset($_SESSION[$prefix][$name]);
		break;
	}
}

//密码加密
function password($password,$salt){
    return md5(md5($password),$salt);
}