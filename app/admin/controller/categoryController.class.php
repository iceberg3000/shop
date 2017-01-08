<?php
class categoryController extends commonController{
    //查看所有的分类
    public function indexAction(){
        $data = D('category')->getData('_tree');                //查询分类数据
        require ACTION_VIEW;
    }  

    //显示添加分类表单
    public function addAction(){
        $id = isset($_GET['id']) ? $_GET['id'] : 0;             //默认选中的ID
        $data = D('category')->getData('_tree');                //查询分类数据
        require ACTION_VIEW;                                    //显示视图
    }

    //执行添加分类操作
    public function addExecAction(){
        //接收变量
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        //添加数据
        $id = M('category')->data(array('pid'=>$pid,'name'=>$name))->add();
        if(isset($_POST['return'])){
            $this->success('直接添加成功','/shop/index.php?p=admin&c=category');
        }else{
            $this->success('添加并返回成功',"/shop/index.php?p=admin&c=category&a=add&id=$pid");
        }
    } 

    //显示分类修改的表单页面
    public function editAction(){
        $id = isset($_GET['id']) ? $_GET['id'] : 0;             //待修改的分类ID
        $data = D('category')->getData('_tree');                //查询分类数据
        isset($data[$id]) || E('您修改的分类不存在');
        require ACTION_VIEW;                                    //显示视图
    }

    //执行分类修改操作
    public function editExecAction(){
        $id = $_POST['id'];             //接收待修改的分类ID
        $pid = $_POST['pid'];           //接收修改后的上级分类ID
        $name = $_POST['name'];         //接收修改后的分类名称
        $Category = D('category');      //创建分类模型
        //验证上级分类ID是否合法
        if(in_array($pid,$Category->getSubIds($id))){
            $this->error('修改失败：不允许将父分类修改为自身或子级分类');
        }
        //修改数据，返回结果
        $Category->data(array('id'=>$id,'name'=>$name,'pid'=>$pid))->save();
        if(isset($_POST['return'])){
            $this->success('修改返回成功','/shop/index.php?p=admin&c=category');
        }else{
            $this->success('直接修改成功',"/shop/index.php?p=admin&c=category&a=edit&id=$id");
        }
    }

    //分类删除
    public function delExecAction(){
        $id = $_POST['id'];
        $Category = D('category');
        //只允许删除最底层分类
        if($Category->exists('pid',$id)){
            $this->error('删除失败，只鸡毛删除最底层分类。');
        }
        //将该分类下的商品设为未分类
        M('goods')->data(array('id'=>$id,'category_id'=>0))->save();
        //删除分类
        $Category->delById($id);
        $this->success('分类删除成功');
    }
}