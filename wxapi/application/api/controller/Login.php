<?php

namespace app\api\controller;

use app\common\lib\Wxapi;
use app\common\lib\Aes;

class Login
{
    public function login($code='')
    {
        $this->datas = $this->params;
        $wxapi = new Wxapi();
        $open = $wxapi->GetOpenId($this->datas['code']);
        $userdata = json_decode($open, true);
        $data = array();
        $data['openid'] = $userdata['openid'];
        $data['expire'] = time();
        $data['rank'] = rand(100000, 999999);
        $aes = new Aes();
        $token = $aes->encrypt(json_encode($data));
        $return = array();
        $return['openid'] = $userdata['openid'];
        $return['token'] = $token;
        return json_encode($return);
    }
    public function checktoken()
    {
        $this->datas = $this->params;
        $token = $this->datas['token'];
        $aes = new Aes();
        $data=json_decode($aes->decrypt($token),true);
        if (time() - intval($data['expire']) > 7000) {
            return show_msg(0,"token已过期！",'',400);
        }
        return $token;
    }
}
