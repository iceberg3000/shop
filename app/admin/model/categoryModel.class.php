<?php
class categoryModel extends Model{
    //查询分类数据
    public function getData($callback=false){
        static $data = null;                //缓存数据库数据
        $data || $data = $this->fetchAll('select * from __CATEGORY__');
        //根据回调函数处理返回的数据
        return $callback ? $this->$callback($data) : $data;
    }

    /**
    *@param array $arr 给定数据
    *@param int $pid 指定从哪个节点开始我
    *@return array 构造好的数组
    */
    private function _tree($arr,$pid=0,$level=0){
        static $tree = array();
        foreach($arr as $v){
            if($v['pid'] == $pid){
                $v['level'] = $level;               //保存递归深度
                $tree[$v['id']] = $v;
                $this->_tree($arr,$v['id'],$level+1);       //递归调用
            }
        }

        return $tree;
    }

    
	//删除分类数据
	public function delById($id){
		$this->data['id'] = $id;
		return $this->exec("delete from __CATEGORY__ where id=:id");
	}

    //查找所有子孙分类ID(返回结果中包含自身ID)
    public function getSubIds($pid){
        $data = $this->_tree($this->getData(),$pid);
        $result = array($pid);              //把自身放入数组
        foreach($data as $v){
            $result[] = $v['id'];
        }
        return $result;
    }
}