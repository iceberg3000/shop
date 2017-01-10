<?php
class goodsModel extends Model{
    //获取数据（参数依次为获取类型，所属分类ID,当前访问的页码）
    public function getData($type='goods',$cids=0,$page=1){
        //根据类型准备查询条件
        if($type=='goods'){             //商品列表页取数据时
            $where = " g.recycle='no'";
        }elseif($type=='recycle'){      //商品回收站取数据时
            $where = " g.recycle='yes'";
        }

        //cids=0,查找未分类商品;cid<0,查找全部商品
        if($cids == 0){
            $where .=' and g.category_id = 0';
        }elseif(is_array($cids)){       //查找分类ID数组
            $where .=' and g.category_id in('.implode(',',$cids).')';

        }
        //获取符合条件的商品总数
        $total = $this->fetchColumn('select count(*) from __GOODS__ as g where'.$where);
        //准备分页查询
        $Page = new page($total,10,$page);      //参数依次为总页数，每页显示条数，当前页码
        $limit = $Page->getLimit();
        //查询数据
        $data = $this->fetchAll("select c.name as category_name,g.category_id,g.id,g.name,g.on_sale,g.stock,g.recommend from __GOODS__ as g left join __CATEGORY__ as c on c.id=g.category_id where $where order by g.id desc limit $limit");
        //返回结果
        return array(
            'data' => $data,        //商品列表数组
            'pagelist' => $Page->show(),       //分页链接HTML
        );
    }

    //修改指定字段（参数依次为主健ID值，待修改字段，修改后的值)
    public function change($id,$name,$value='yes'){
        $value == 'yes' || $value = 'no';       //排除非法值
        $this->data(array('id'=>$id,$name=>$value))->exec("update __GOODS__ set `$name`=:$name where `id`=:id");
    }

    //彻底删除商品
    public function delete($id){
        $this->data['id'] = $id;
        $this->exec("delete from __GOODS__ where `id`=:id and `recycle`='yes'");
    }
}