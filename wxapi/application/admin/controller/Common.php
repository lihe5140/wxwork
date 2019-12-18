<?php

namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;

class Common extends Controller
{
    public function _initialize()
    {
        if (!session('username')) {
            $this->error('请先登录系统！', 'Login/index');
        }
    }
}
