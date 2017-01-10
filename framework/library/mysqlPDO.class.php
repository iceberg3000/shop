<?php
//基于PDO扩展的MySQL数据库操作类
class mysqlPDO{
    protected static $db = null;                //保存PDO实例
    protected $data = array();                  //保存操作数据
    public function __construct(){
        isset(self::$db) || self::_connect();   //PDO单例模式
    }
    private function __clone(){}                //禁止克隆
    //连接目标服务器（本方法只在构造方法中调用一次）
    private static function _connect(){
        //$config = $GLOBALS['dbConfig'];         //通过全局变量获取数据库配置信息
        $config = C('DB_CONFIG');                 //获取项目的数据库配置信息
        //准备PDO的DSN连接信息
        $dsn = "{$config['db']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        try{
            self::$db = new PDO($dsn,$config['user'],$config['pass']);  //连接数据库
        }catch(PDOException $e){
            //exit('数据库连接失败：'.$e->getMessage());
            //E('数据库连接失败：'.$e->getMessage());     //输出错误并停止
            //连接失败
            if(APP_DEBUG){
                E('数据库连接失败：'.$e->getMessage());     //输出错误并停止
            }else{
                E('数据库连接失败');
            }
        }
    }
    
    /**
    * 通过预处理方式执行SQL
    * @param string $sql 执行的SQL语句模板
    * @param bool $bath 是否手批量操作
    * @param object PDOStatement
    */
    public function query($sql,$batch=false){
        //取出成员属性中的数据并清空
        $data = $batch ? $this->data : array($this->data);
        $this->data = array();
        //通过预处理方法执行SQL
        $stmt = self::$db->prepare($sql);
        foreach($data as $v){
            if($stmt->execute($v)===false){
                //exit('数据库操作失败：'.implode('-',$stmt->errorInfo()));
                if(APP_DEBUG){
                    exit('数据库操作失败：'.implode('-',$stmt->errorInfo())."\nSQL语句：".$sql);
                }else{
                    exit('数据库操作失败：');
                }
            }
        }
        return $stmt;
    }
    
    /**
    * @param array $data 需要保存的数据
    * @return 返回对象自身用于链式调用
    */
    public function data($data){
        $this->data = $data;
        return $this;
    }

    //取得一行结果
    public function fetchRow($sql){
        return $this->query($sql)->fetch(PDO::FETCH_ASSOC);
    }
    //取得所有结果
    public function fetchAll($sql){
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    //取得一列结果
    public function fetchColumn($sql){
        return $this->query($sql)->fetchColumn();
    }
    //获取最后插入的ID
    public function lastInsertId(){
        return self::$db->lastInsertId();
    }
}