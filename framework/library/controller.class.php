<?php
//基础控制器
class controller{
    //方法不存在时报错退出
    public function __call($name,$args){
        E('您访问的操作不存在！');
    }
    //重定向
    protected function redirect($url){
        header("Location:$url");
        exit;
    }
}