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
    public function uploadfilename(){

    	$time=time();
		$rand=rand(0000,9999);
		$name=$time+$rand;
		$name=dechex($name);
		return $name;
    }
    
}
