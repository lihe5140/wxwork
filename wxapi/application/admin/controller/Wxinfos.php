<?php

namespace app\admin\controller;

use app\admin\controller\Common;
use think\Db;

class Wxinfos extends Common
{

	public function index()
	{
		$wxinfo = Db::name('wxinfos')->order('wx_id asc')->select();
		// print_r($user);
		$this->assign('wxinfo', $wxinfo);
		return $this->fetch('index');
	}
	public function add()
	{
		// $wxinfo = Db('wxinfos')->select();
		if (request()->isPost()) {
			$data = input('post.');
			$data['wx_ctime'] = time();
			$name = action('uploadfilename');
			$file = request()->file('wx_litpic');
			if (!empty($file)) {
				$info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'static/uploads/' . date("Ymd"), $name);
				// var_dump($info);die;
				if ($info) {
					$data['wx_litpic'] = SITE_URL.'/static/uploads/' . date("Ymd") . "/" . $info->getSaveName();
				} else {
					// 上传失败获取错误信息
					$this->error($file->getError());
				}
			}
			$save = Db('wxinfos')->insert($data);
			if ($save) {
				$this->success('绑定成功！', 'wxinfos/index');
			} else {
				$this->error('绑定失败！');
			}
		}
		return $this->fetch('add');
	}
	public function editpwd()
	{
		$id = input('id');
		$user = Db('admin')->find($id);
		if (request()->isPost()) {
			$data = [
				'id' => input('id'),
				'username' => input('username'),
			];
			if (input('password')) {
				$data['password'] = input('password');
			} else {
				$data['password'] = $user['password'];
			}
			// print_r($data);
			$save = Db('admin')->update($data);
			if ($save) {
				$this->success('修改管理员密码成功！', 'admin/index');
			} else {
				$this->error('修改管理员密码失败！');
			}
		}
		$this->assign('user', $user);
		return $this->fetch('edit');
	}
}
