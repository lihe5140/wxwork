<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Request;
class Article extends Common{

	public function index(){
		$article=Db('article')->alias('a')
			->join('message m','m.m_artid=a.art_id','left')
			->join('wxinfos w','w.wx_id=a.art_wxid')
			->group('a.art_id')
			->field('a.art_id,a.art_title,a.art_digest,a.art_litpic,a.art_ctime,w.wx_name,a.art_wxid,count(m.m_artid) as count')
			->order('art_id desc')
			->select();
		$this->assign('article',$article);
		return $this->fetch('index');
	}
	public function add(){
		$wxinfo=Db('wxinfos')->select();
		if(request()->isPost()){
			$data=input('post.');
			$data['art_ctime']=time();
			$name = action('uploadfilename');
			$file = request()->file('art_litpic');
			if (!empty($file)) {
				$info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'static/uploads/' . date("Ymd"), $name);
				// var_dump($info);die;
				if ($info) {
					$data['art_litpic'] = SITE_URL.'/static/uploads/' . date("Ymd") . "/" . $info->getSaveName();
				} else {
					// 上传失败获取错误信息
					$this->error($file->getError());
				}
			}
         	$save=Db('article')->insert($data);
			if($save){
				$this->success('添加成功！','article/index');
			}else{
				$this->error('添加失败！');
			}
		}
		$this->assign('wxinfo',$wxinfo);
		return $this->fetch('add');
	}
	public function edit(){
		$id=input('art_id');
        $wxinfo=Db('wxinfos')->select();
		$article=Db('article')->find($id);
		if(request()->isPost()){
			$data=[
                'art_id'=>$id,
                'art_title'=>input('art_title'),
                'art_digest'=>input('art_digest'),
                'art_wxid'=>input('art_wxid'),
			];
			$name = action('uploadfilename');
			$file = request()->file('art_litpic');
			if(!empty($file)){
				$info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'static/uploads/' . date("Ymd"), $name);
				if ($info) {
					$data['art_litpic'] = SITE_URL . '/static/uploads/' . date("Ymd") . "/" . $info->getSaveName();
				} else {
					$this->error($file->getError());
				}
			}
			$save=Db('article')->update($data);
			if($save){
				$this->success('修改成功！','article/index');
			}else{
				$this->error('修改失败！');
			}
		}
		$this->assign('wxinfo',$wxinfo);
		$this->assign('article',$article);
		return $this->fetch('edit');
	}
	public function del(){
		$id=input('art_id');
		$del=Db('article')->delete($id);
		if($del){
			$this->success('删除成功！','article/index');
		}else{
			$this->error('删除失败！');
		}
	}
}
