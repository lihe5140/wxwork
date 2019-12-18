<?php

namespace app\admin\controller;

use app\admin\Controller\Common;

class Message extends Common
{
    public function index()
    {
        $artid = input('m_artid');
        $message = Db('message')->where('m_artid', $artid)->order('m_id desc')->select();
        $this->assign('message', $message);
        return $this->fetch('index');
    }
    public function ischeck()
    {
        $data = input('post.');
        $res = Db('message')->update($data);
        if (!$res) {
            return 0;
        }
    }
    public function istop()
    {
        $data = input('post.');
        $res = Db('message')->update($data);
        if (!$res) {
            return 0;
        }
    }
    public function isfine()
    {
        $data = input('post.');
        $res = Db('message')->update($data);
        if (!$res) {
            return 0;
        }
    }
    public function view()
    {
        $id = input('m_id');
        $message = Db('message')->find($id);
        $this->assign('message', $message);
        return $this->fetch('view');
    }
    public function reply()
    {
        $id = input('m_id');
        $message = Db('message')->find($id);
        $this->assign('message', $message);
        return $this->fetch('view');
    }
    // public function del()
    // {
    //     $id = input('id');
    //     $del = Db('message')->delete($id);
    //     if ($del) {
    //         $this->success('删除成功！', 'message/index');
    //     } else {
    //         $this->error('删除失败！');
    //     }
    // }
}
