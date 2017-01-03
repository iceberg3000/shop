<?php
require './mysqlPDO.class.php';
require './categoryModel.class.php';
$dbConfig = array(
    'db' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'user' => 'root',
    'pass' => '123456',
    'charset' => 'utf8',
    'dbname' => 'itcast_shop',
);

// $db = new mysqlPDO();
// echo '<pre>';
// var_dump($db->fetchAll('show tables'));
// echo '</pre>';

// $Category = new categoryModel();
// $Category->addData('phone','0');
// echo '<pre>';
// var_dump($Category->getData());
// echo '</pre>';

//获取控制器，操作名称
$c = isset($_GET['c']) ? $_GET['c'] : '';
$a = isset($_GET['a']) ? $_GET['a'] : '';
$c_name = $c.'Controller';
$a_name = $a.'Action';

require "./{$c_name}.class.php";
$Controller = new $c_name();
$Controller->$a_name();
