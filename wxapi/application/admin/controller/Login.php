<?php

namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin;

class Login extends Controller
{
    public function index()
    {
        if (request()->isPost()) {

            $data = input('post.');
            $user = Db('admin')->where('username', '=', $data['username'])->find();
            if ($user) {
                if ($user['userpwd'] == $data['userpwd']) {
                    session('username', $user['username']);
                    session('uid', $user['id']);
                    $this->success('登录成功，正在为您跳转...', 'index/index');
                } else {
                    $this->error('密码错误');
                }
            }
        }
        return $this->fetch('index');
    }
    // 退出登录
    public function logout()
    {
        //销毁session
        session("username", NULL);
        //跳转页面
        $this->redirect('Login/index');
    }
}
