<?php

namespace app\api\controller;

use app\common\lib\Aes;
use think\Request;

class HandleWxAccount extends Common
{



    /**
     * 绑定微信公众号
     *
     * @param String $appid
     * @param String $appsecret
     * @return Json
     */
    public function save($appid, $appsecret)
    {
        $res = db('wxinfo')->where('appid', $appid)->find();
        if (empty($res)) {
            $data = [];
            $aes = new Aes();
            $data['appid'] = $appid;
            $data['appsecret'] = $aes->encrypt($appsecret);
            $data['handletime'] = time();
            $insert = db('wxinfo')->insert($data);
            if ($insert) {
                return show_msg(1, '添加成功！', '', 200);
            }
        } else {
            return show_msg(0, '公众号已经绑定！', '', 200);
        }
    }
    public function edit()
    {
        $data = input('?post.id');
        return $data;
        die;
        // $res=Db('wxinfo')->update($data,$id);
        $res = db('wxinfo')->where('id', $id)->update($data);
        if ($res) {
            return show_msg(1, '修改成功！', '', 200);
        }
    }
    // 删除
    public function delete($id)
    {
        $res = Db('wxinfo')->delete($id);
        if ($res) {
            return show_msg(1, '删除数据成功！', $res, 200);
        }
    }
}
