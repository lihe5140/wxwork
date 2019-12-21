<?php

namespace app\api\controller;

class User extends Common
{

    public $datas;

    public function checkuserinfo(){
        $this->datas = $this->params;
        $data['u_id']=$this->datas['u_id'];
        $data['u_openid']=$this->datas['u_openid'];
        $res=Db('user')->where($data)->find();
        if($res['u_name']!=$this->datas['u_name']  || $res['u_avatar']!=$this->datas['u_avatar']){
            $update=array();
            $upadte['u_name']=$this->datas['u_name'];
            $upadte['u_avatar']=$this->datas['u_avatar'];
            $result=Db('user')->where($data)->update($update);
            if($result){
                return show_msg(0, '更新用户信息成功', '', 200);
            }else{
                return show_msg(2, '更新失败！', null, 400);
            }
        }else{
            return show_msg(0, '用户已是最新数据', null, 200);
        }
    }
}
