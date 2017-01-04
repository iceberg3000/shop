<?php
class categoryController{
    //分类列表
    public function indexAction(){
        $Category = new categoryModel();
        $data = $Category->getData();
        require ACTION_VIEW;
    }

    public function addAction(){}

    public function editAction(){}

    public function delAction(){}
}