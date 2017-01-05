<?php
//项目配置文件
return array(
    //数据库配置
    'DB_CONFIG'=>array(
        'db' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'root',
        'pass' => '123456',
        'charset' => 'utf8',
        'dbname' => 'itcast_shop',
    ),
    'DB_PREFIX' => 'shop_', //数据库表前缀
	//保存在Cookie中的PHPSESSID是否使用HttpOnly
	'PHPSESSID_HTTPONLY' => true,
	//配置时区
	'DEFAULT_TIMEZONE' => 'Asia/Shanghai',
	//Session配置
	'SESSION_PREFIX' => 'shop',   //Session 前缀
	'SESSION_EXPIRE' => 1440,     //Session有效期
	'SESSION_DB' => true          //Session入库开关
);