<?php

namespace app\api\controller;

use think\Db;

class Message extends Common
{
    
    public function index()
    {
        $data = Db::table('msg')->order('id desc')->select();
        if ($data != '') {
            return show_msg(1, '获取数据成功！', $data, 200);
        } else {
            return show_msg(0, '数据为空', '', 200);
        }
    }
    // 留言列表
    public function GetMsg($a_id)
    { }
    // 添加留言
    public function AddMsg()
    { 
        
    }
    // 删除留言
    public function DelMsg()
    { }
    // 点赞
    public function good()
    { }
    // 置顶
    public function istop(){

    }
}
