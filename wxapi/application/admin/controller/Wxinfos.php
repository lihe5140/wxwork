<?php

namespace app\admin\controller;

use app\admin\controller\Common;
use think\Db;

class Wxinfos extends Common
{

	public function index()
	{
		$wxinfo = Db::name('wxinfos')->order('wx_id asc')->select();
		$this->assign('wxinfo', $wxinfo);
		return $this->fetch('index');
	}
	public function add()
	{
		// $wxinfo = Db('wxinfos')->select();
		if (request()->isPost()) {
			$data = input('post.');
			$data['wx_ctime'] = time();
			$wxinfo = Db('wxinfos')->where('wx_appid',$data['wx_appid'])->find();
			if(!empty($wxinfo)){
				$this->error('公众号已经绑定！');
			}
			$name = action('uploadfilename');
			$file = request()->file('wx_litpic');
			if (!empty($file)) {
				$info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'static/uploads/' . date("Ymd"), $name);
				// var_dump($info);die;
				if ($info) {
					$data['wx_litpic'] = SITE_URL . '/static/uploads/' . date("Ymd") . "/" . $info->getSaveName();
				} else {
					// 上传失败获取错误信息
					$this->error($file->getError());
				}
			}
			$save = Db('wxinfos')->insert($data);
			if ($save) {
				$this->success('公众号绑定成功！', 'wxinfos/index');
			} else {
				$this->error('公众号绑定失败！');
			}
		}
		return $this->fetch('add');
	}
	public function edit()
	{
		$id = input('wx_id');
		$wxinfos = Db('wxinfos')->find($id);
		if (request()->isPost()) {
			$data = [
				'wx_id' => $id,
				'wx_name' => input('wx_name'),
				'wx_digest' => input('wx_digest'),
			];
			$name = action('uploadfilename');
			$file = request()->file('wx_litpic');
			if(!empty($file)){
				$info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'static/uploads/' . date("Ymd"), $name);
				if ($info) {
					$data['wx_litpic'] = SITE_URL . '/static/uploads/' . date("Ymd") . "/" . $info->getSaveName();
				} else {
					$this->error($file->getError());
				}
			}
			$save = Db('wxinfos')->update($data);
			if ($save) {
				$this->success('修改成功！', 'wxinfos/index');
			} else {
				$this->error('修改失败！');
			}
		}
		$this->assign('wxinfos', $wxinfos);
		return $this->fetch('edit');
	}
	public function del()
	{
		$id = input('wx_id');
		$del = Db('wxinfos')->delete($id);
		if ($del) {
			$this->success('删除成功！', 'wxinfos/index');
		} else {
			$this->error('删除失败！');
		}
	}
}
