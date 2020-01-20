<?php

namespace app\api\controller;

use app\common\lib\Aes;
use think\Request;

class Message extends Common
{
    public $datas;
    /**
     * 查询文章留言接口
     *
     * @return Json
     */
    public function getmsg()
    {
        //1. 接收参数
        $this->datas = $this->params;
        //2.检查参数
        if (!isset($this->datas['m_artid'])) {
            return show_msg(0, '查询失败！', '', 400);
        }
        if (!isset($this->datas['num'])) {
            $this->datas['num'] = 100;
        }
        if (!isset($this->datas['page'])) {
            $this->datas['page'] = 1;
        }
        $count = db('message')->count();
        $page_num = ceil($count / $this->datas['num']);
        // $join = [['article a', 'a.art_id = m.m_artid']];
        $where = array(
            'm_ischeck'=>1,
            'm_artid'=>$this->datas['m_artid']
        );
        $res = db('message')
            // ->alias('m')
            // ->join($join)
            ->where($where)
            ->page($this->datas['page'], $this->datas['num'])
            ->select();
        if ($res === false) {
            return show_msg(0, '查询失败！', '', 400);
        } else if (empty($res)) {
            return show_msg(1, '暂无数据！', '', 200);
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
    public function addmsg()
    {
        //接收参数
        $this->datas = $this->params;
        $this->datas['m_time'] = time();
        $result = db('message')->insertGetId($this->datas);
        //返回执行结果
        if (!empty($result)) {
            $this->datas['m_id']=$result;
            return show_msg(1, '留言成功！', $this->datas, 200);
        } else {
            return show_msg(0, '留言失败！', '', 400);
        }
    }
    public function update()
    {
        $this->datas = $this->params;
        $res = db('article')->where('art_id', $this->datas['art_id'])->update($this->datas);
        if (!empty($res)) {
            return show_msg(1, '修改文章成功！', $res, 200);
        } else {
            return show_msg(0, '修改文章失败！', '', 400);
        }
    }
    public function getusermsg()
    {
        //1. 接收参数
        $this->datas = $this->params;
        //2.检查参数
        if (!isset($this->datas['m_artid']) || !isset($this->datas['m_uid'])) {
            return show_msg(0, '查询失败！', '', 400);
        }
        if (!isset($this->datas['num'])) {
            $this->datas['num'] = 10;
        }
        if (!isset($this->datas['page'])) {
            $this->datas['page'] = 1;
        }
        // $count = db('message')->count();
        // $page_num = ceil($count / $this->datas['num']);
        // $join = [['article a', 'a.art_id = m.m_artid'],['user u','u.u_id=m.m_uid']];
        $where = array(
            // 'm_ischeck'=>1,
            'm_artid'=>$this->datas['m_artid'],
            'm_uid' =>$this->datas['m_uid']
        );
        $res = db('message')
        ->alias('m')
        // ->join($join)
        ->where($where)
        ->page($this->datas['page'], $this->datas['num'])
        ->select();
        if ($res === false) {
            return show_msg(0, '查询失败！', '', 400);
        } else if (empty($res)) {
            return show_msg(1, '暂无数据！', array(), 200);
        } else {
            return show_msg(1, '查询成功！', $res, 200);
        }
    }   
    public function delmsg()
    {
        $this->datas = $this->params;
        $where=array(
            'm_id'=>$this->datas['m_id'],
            // 'm_uid'=>$this->datas['m_uid'],
        );
        $res = db('message')->where($where)->delete();
        if (!empty($res)) {
            return show_msg(1, '删除文章成功！', $res, 200);
        } else {
            return show_msg(0, '删除文章失败！', '', 400);
        }
    }
}
