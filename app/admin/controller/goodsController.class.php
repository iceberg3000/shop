<?php
class goodsController extends commonController{
    //商品列表
    public function indexAction(){
        $cid = isset($_GET['cid']) ? $_GET['cid'] : -1;             //分类ID
        $page = isset($_GET['page']) ? $_GET['page'] : 1;           //页码
        $data = array();
        //查询分类数据
        $Category = D('category');
        $data['category'] = $Category->getData('_tree');
        //如果分类ID大于0，则取出所有子分类ID
        $cids = ($cid>0) ? $Category->getSubIds($cid) : $cid;
        //获取商品列表
        $data['goods'] = D('goods')->getData('goods',$cid,$page);
        //超出页码时自动返回0
        if(empty($data['goods']['data']) && $page>1){
            $this->redirect("/shop/index.php?p=admin&c=goods&a=index&cid=$cid");
        }
        require ACTION_VIEW;
    }
}