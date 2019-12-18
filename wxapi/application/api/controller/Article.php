<?php

namespace app\api\controller;

use app\common\lib\Aes;
use think\Request;

class Article extends Common
{
    public $datas;
    /**
     * 查询文章列表接口
     *
     * @return Json
     */
    public function index()
    {
        //1. 接收参数
        $this->datas = $this->params;
        //2.检查参数
        if (!isset($this->datas['num'])) {
            $this->datas['num'] = 10;
        }
        if (!isset($this->datas['page'])) {
            $this->datas['page'] = 1;
        }
        $count = db('article')->count();
        $page_num = ceil($count / $this->datas['num']);
        $field = 'art_id,art_title,art_digest,art_wxid,wx_name,wx_litpic';
        $join = [['wxinfos w', 'w.wx_id = a.art_wxid']];
        $res = db('article')->alias('a')->field($field)->join($join)->page($this->datas['page'], $this->datas['num'])->select();
        if ($res === false) {
            return show_msg(0, '查询失败！', '', 400);
        } else if (empty($res)) {
            return show_msg(2, '暂无数据！', null, 200);
        } else {
            $return_data['article'] = $res;
            $return_data['page_num'] = $page_num;
            return show_msg(1, '查询成功！', $res, 200);
        }
    }
    /**
     * 绑定微信公众号
     *
     * @param String $appid
     * @param String $appsecret
     * @return Json
     */
    public function save()
    {
        //接收参数
        $this->datas = $this->params;
        $this->datas['art_ctime'] = time();
        $result = db('article')->insertGetId($this->datas);
        //返回执行结果
        if (!empty($result)) {
            return show_msg(1, '添加文章成功！', $result, 200);
        } else {
            return show_msg(0, '添加文章失败！', '', 400);
        }
    }

    public function update()
    {
        $this->datas = $this->params;
        // dump($this->datas['wx_id']);die;
        $res = db('article')->where('art_id', $this->datas['art_id'])->update($this->datas);
        if (!empty($res)) {
            return show_msg(1, '修改文章成功！', $res, 200);
        } else {
            return show_msg(0, '修改文章失败！', '', 400);
        }
    }
    public function delete()
    {
        $this->datas = $this->params;

        $res = db('article')->where('art_id', $this->datas['art_id'])->delete();
        if (!empty($res)) {
            return show_msg(1, '删除文章成功！', $res, 200);
        } else {
            return show_msg(0, '删除文章失败！', '', 400);
        }
    }
}
