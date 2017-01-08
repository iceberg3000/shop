<?php
//基础控制器
class controller{
    public function __construct(){
    }
    //方法不存在时报错退出
    public function __call($name,$args){
        E('您访问的操作不存在！');
    }
    //重定向
    protected function redirect($url){
        header("Location:$url");
        exit;
    }

    //Ajax-返回成功的JSON信息
    protected function success($msg='',$target=''){
        $this->ajaxReturn(array('ok'=>true,'msg'=>$msg,'target'=>$target));
    }

    //Ajax-返回失败的JSON信息
    protected function error($msg='',$target=''){
        $this->ajaxReturn(array('ok'=>false,'msg'=>$msg,'target'=>$target));
    }
    //Ajax-返回JSON信息
    protected function ajaxReturn($data){
        exit(json_encode($data));
    }
}