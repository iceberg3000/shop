<?php
//后台管理员模型
class adminModel extends model{
    //验证用于登录的用户名和密码
    public function checkLogin($username,$password){
        $this->data['username'] = $username;
        $data = $this->fetchRow('select `id`,`username`,`password`,`salt` from __ADMIN__ where `username`=:username');
        //var_dump($data);
        //var_dump(password($password,$data['salt']));
        if($data && $data['password']==password($password,$data['salt'])){
            return array('id'=>$data['id'],'name'=>$data['username']);
        }
        return false;
    }    
}