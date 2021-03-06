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
        $where = array(
            'm_ischeck' => 1,
            'm_artid' => $this->datas['m_artid'],
        );
        $res = db('message')
            ->alias('m')
            ->join('zan z', 'z.z_mid=m.m_id and z_status = 1 and z_uid='.$this->datas['z_uid'], 'left')
            ->group('m.m_id')
            ->field('m.*,count(z_id) as iszan')
            ->where($where)
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
            $this->datas['m_id'] = $result;
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
        $where = array(
            'm_artid' => $this->datas['m_artid'],
            'm_uid' => $this->datas['m_uid']
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
        $where = array(
            'm_id' => $this->datas['m_id'],
            // 'm_uid'=>$this->datas['m_uid'],
        );
        $res = db('message')->where($where)->delete();
        if (!empty($res)) {
            return show_msg(1, '删除文章成功！', $res, 200);
        } else {
            return show_msg(0, '删除文章失败！', '', 400);
        }
    }
    public function zan(){
        $this->datas=$this->params;
        $res=db('zan')->where(['z_mid'=>$this->datas['z_mid'],'z_uid'=>$this->datas['z_uid']])->find();
        $mes=db('message')->where(['m_id'=>$this->datas['z_mid']])->find();
        if($res){
            $updatecount['m_goodnum']=$res['z_status']==1?$mes['m_goodnum']-1:$mes['m_goodnum']+1;
            $status=$res['z_status']==1?0:1;
            $this->datas['z_status']=$status;
            $result=db('zan')->where(['z_id'=>$res['z_id']])->update($this->datas);
            $good_result=db('message')->where(['m_id'=>$this->datas['z_mid']])->update($updatecount);
            return show_msg(1, '操作成功！', $status, 200);
        }else{
            $this->datas['z_status']=1;
            $this->datas['z_ctime']=time();
            $updatecount['m_goodnum']=$mes['m_goodnum']+1;
            $result=db('zan')->insert($this->datas);
            $good_result=db('message')->where(['m_id'=>$this->datas['z_mid']])->update($updatecount);
            return show_msg(1, '操作成功！', 1, 200);
        }
        if(!$result || !$good_result){
            return show_msg(0, '操作失败！', '', 400);
        }
    }

}
