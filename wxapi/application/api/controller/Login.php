<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;
use app\common\lib\Wxapi;
use app\common\lib\Aes;

class Login extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->req = Request::instance();
        // 验证参数,返回成功过滤后的参数数组
        // dump($this->req->header());
        // dump($this->req->header('code'));die;
        // $this->login($this->req->header('code'));

        // $this->params = $this->checkParams($this->req->param(true));
    }
    public function login()
    {
        $this->req = Request::instance();
        $wxapi = new Wxapi();
        $open = $wxapi->GetOpenId($this->req->param('code'));
        $userdata = json_decode($open, true);
        if(!array_key_exists('errcode',$userdata)){
            $res = Db('user')->where('u_openid', $userdata['openid'])->find();
            if ($res) {
                $data = array();
                $data['u_token'] = $this->gen_token($userdata['openid'], time(), rand(100000, 999999));
                $data['u_time'] = time();
                Db('user')->where('u_openid', $userdata['openid'])->update($data);
                $return = array();
                $return['openid'] = $userdata['openid'];
                $return['token'] = $data['u_token'];
                return json_encode($return);
            }else{
                $time=time();
                $data = array();
                $data['u_openid']=$userdata['openid'];
                $data['u_token'] = $this->gen_token($userdata['openid'],$time,rand(100000, 999999));
                $data['u_time'] = $time;
                Db('user')->insertGetId($data);
                $return = array();
                $return['openid'] = $userdata['openid'];
                $return['token'] = $data['u_token'];
                return json_encode($return);
            }
        }else{
            return $open;
        }
    }
    public function gen_token($openid, $time, $rank)
    {
        $data = array();
        $data['openid'] = $openid;
        $data['expire'] = $time;
        $data['rank'] = $rank;
        $aes = new Aes();
        return $aes->encrypt(json_encode($data));
    }
    public function checktoken()
    {
        $token=$this->req->header('token');
        $aes = new Aes();
        $data = json_decode($aes->decrypt($token), true);
        // echo time() - intval($data['expire']);
        if (time() - intval($data['expire']) > 7000) {
            return show_msg(10303, "token已过期！", 'errCode', 400);
        }
        return show_msg(10300,"token可用",'successCode',200);
    }
}