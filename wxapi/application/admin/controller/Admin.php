<?php
namespace app\admin\controller;
use app\admin\Controller\Common;
use think\Db;
class Admin extends Common{

	public function index(){
		$user=Db::name('admin')->order('id desc')->select();
		// print_r($user);
		$this->assign('user',$user);
		return $this->fetch('index');
	}
	public function editpwd(){
		$id=input('id');
		$user=Db('admin')->find($id);
		if(request()->isPost()){
			$data=[
				'id'=>input('id'),
				'username'=>input('username'),
			];
			if(input('password')){
				$data['password']=input('password');
			}else{
				$data['password']=$user['password'];
			}
			// print_r($data);
			$save=Db('admin')->update($data);
			if($save){
				$this->success('修改管理员密码成功！','admin/index');
			}else{
				$this->error('修改管理员密码失败！');
			}
		}
		$this->assign('user',$user);
		return $this->fetch('edit');
	}
}




?>