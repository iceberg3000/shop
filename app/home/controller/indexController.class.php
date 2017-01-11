<?php
//显示前台控制器
class indexController extends commonController{
    //显示前台首页
    public function indexAction(){
        $data = array();
        //获得分类列表
        $data['category'] = D('category')->getData('_childList');
        //查询推荐商品
        $data['best'] = D('goods')->getBest();
        //视图
        $this->title = "商城前台首页";
        require ACTION_VIEW;
    }

    //查找商品
    public function findAction(){
        //获取参数
        $page = I('page','get','id',1);//当前页码
        $cid = I('cid','get','id',-1);//分类ID
        //实例化模型
        $Category = D('Category');
        //如果分类ID大于0，则取出所有子分类ID
        $cids = ($cid>0) ? $Category->getSubIds($cid) : $cid;
        //获取商品列表
        $data['goods'] = D('Goods')->getData($cids,$page);
        //防止空页被访问
        if(empty($data['goods']['data']) && $page>1){
            $this->redirect("/shop/index.php?a=find&cid=$cid");
        }
        //查询分类列表
        $data['Category'] = $Category->getFamily($cid);
        $this->title = '商品列表';
        require ACTION_VIEW;
    }
}