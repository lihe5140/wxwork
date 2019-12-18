<?php

namespace app\api\controller;

use app\common\lib\Curl;

class GetWxArticle extends Common
{
    /**
     * 更新公众号接口数据到数据库
     *
     * @param [string] $type  
     * @param [int] $offset
     * @param [int] $count
     * @return void
     */
    public function list($type, $offset, $count)
    {
        $data = [];
        $data['type'] = $type;
        $data['offset'] = $offset;
        $data['count'] = $count;
        $data = json_encode($data);
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . $this->getAccessToken();
        $curl=new Curl();
        $res = $curl::curl_post_https($url, $data);
        $res = json_decode($res, true);
        foreach ($res['item'] as $value) {
            $res_sel = db('media')->where('media_id', $value['media_id'])->find();
            if (!$res_sel) {
                $dat = array();
                $dat['media_id'] = $value['media_id'];
                $dat['update_time'] = $value['update_time'];
                $InsertID = db('media')->insertGetId($dat);
            } else {
                $InsertID = $res_sel['m_id'];
            }
            foreach ($value['content']['news_item'] as $val) {
                $artData = array();
                $artData['title'] = $val['title'];
                $artData['author'] = $val['author'];
                $artData['digest'] = $val['digest'];
                $artData['content'] = $val['content'];
                $artData['thumb_media_id'] = $val['thumb_media_id'];
                $artData['url'] = $val['url'];
                $artData['media_id'] = $InsertID;
                if (!db('article')->where('title', $artData['title'])->count()) {
                    db('article')->insert($artData);
                }
            }
        }
        return show_msg(1, '更新文章列表成功', '', 200);
    }
}
