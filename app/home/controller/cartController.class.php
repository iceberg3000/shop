<?php
//购物车控制器
class cartController extends commonController{
    public function __construct(){
        parent::__construct();
        IS_LOGIN || $this->redirect('/shop/index.php?c=user&a=login');      //检查登录
    }
    //添加到购物车
    public function addExecAction(){
        $id = I('id','post','id');
        $num = I('num','post','id');
        //添加到购物车
        D('Shopcart')->addCart($id,$this->user['id'],$num);
        $this->success('添加购物车成功');
    }

    //购物车列表
    public function indexAction(){
        $data = D('shopcart')->getData($this->user['id']);
        require ACTION_VIEW;
    }

    //从购物车删除
    public function delExecAction(){
        $id = I('id','post','id');
        D('shopcart')->delete($id,$this->user['id']);
        $this->success('删除成功');
    }
}