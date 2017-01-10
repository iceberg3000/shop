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
        //var_dump($cid);
        $cids = ($cid > 0) ? $Category->getSubIds($cid) : $cid;
        //var_dump($cids);
        //获取商品列表
        $data['goods'] = D('goods')->getData('goods',$cids,$page);
        //超出页码时自动返回0
        if(empty($data['goods']['data']) && $page>1){
            $this->redirect("/shop/index.php?p=admin&c=goods&a=index&cid=$cid");
        }
        require ACTION_VIEW;
    }

    //显示商品添加表单页面
    public function addAction(){
        //查询分类数据
        $data = array();
        $data['category'] = D('category')->getData('_tree');
        require ACTION_VIEW;
    }
    //执行商品添加操作
    public function addExecAction(){
        $data = $_POST;             //接收表单数据;
        $data['thumb']='thumb';
        $data['album']='album';
        $data['desc']='desc';
        //$this->_createThumb($data['thumb']);       //为封面图创建缩略图
        M('goods')->data($data)->add();             //保存数据
        if(isset($_POST['return'])){                //返回处理结果
            $this->success('','/shop/index.php?p=admin&c=goods&a=index');
        }else{
            $this->success('','/shop/index.php?p=admin&c=goods&a=add');
        }
    }

    //为封面图创建缩略图
    private function _createThumb($file){
        $path = './public/upload';
        $small = "$path/small/$file";
        $file = "$path/$file";
        //创建缩略图
        is_file($small) || image::thumb($file,220,220,$small);
    }

    //修改单个字段状态值
    public function changeExecAction(){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $value = $_POST['value'];
        $allow_name = array('recycle','on_sale','recommend');               //允许修改的字段
        if(in_array($name,$allow_name)){                                    //判断请求的字段是否允许修改
            D('goods')->change($id,$name,$value);                           //修改字段
            $this->success('修改成功');
        }else{
            $this->error('修改失败，指定字段不允许修改.');
        }
    }

    

}