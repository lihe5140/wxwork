<?php

namespace app\api\controller;

use app\common\lib\Aes;
use think\Request;

class HandleWx extends Common
{
    public $datas;
    /**
     * 查询绑定的微信公众号列表接口
     *
     * @return Json
     */
    public function getwxlist()
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
        $count = db('wxinfos')->count();
        $page_num = ceil($count / $this->datas['num']);
        $field = 'wx_id,wx_name,wx_digest,wx_ctime';
        $res = db('wxinfos')->field($field)->page($this->datas['page'], $this->datas['num'])->select();
        if ($res === false) {
            return show_msg(0, '查询失败！', '', 400);
        } else if (empty($res)) {
            return show_msg(2, '暂无数据！', null, 200);
        } else {
            $return_data['wxinfos'] = $res;
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
    public function addwx()
    {
        //接收参数
        $this->datas = $this->params;
        // dump($this->datas);die;
        $this->datas['wx_ctime'] = time();
        //检查是否驻入
        $res = db('wxinfos')->where('wx_appid', $this->datas['wx_appid'])->find();
        if ($res) {
            return show_msg(2, '公众号已经驻入！', '', 200);
            return false;
        }
        $result = db('wxinfos')->insertGetId($this->datas);
        //返回执行结果
        if (!empty($result)) {
            return show_msg(1, '公众号驻入成功！', $result, 200);
        } else {
            return show_msg(0, '公众号驻入失败！', '', 400);
        }
    }
    public function updatewx()
    {
        $this->datas = $this->params;
        // dump($this->datas['wx_id']);die;
        $res = db('wxinfos')->where('wx_id', $this->datas['wx_id'])->update($this->datas);

        if (!empty($res)) {
            return show_msg(1, '修改公众号信息成功！', $res, 200);
        } else {
            return show_msg(0, '修改公众号信息失败！', '', 400);
        }
    }
    public function deletewx()
    {
        $this->datas = $this->params;
        
        $res = db('wxinfos')->where('wx_id', $this->datas['wx_id'])->delete();
        if (!empty($res)) {
            return show_msg(1, '删除公众号信息成功！', $res, 200);
        } else {
            return show_msg(0, '删除公众号信息失败！', '', 400);
        }
    }
}
