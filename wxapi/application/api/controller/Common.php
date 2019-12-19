<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;
use app\common\lib\Wxapi;

class Common extends Controller
{
    protected $req; //用来处理客户端传递过来的参数
    protected $validater; //用来验证数据/参数
    protected $params; //过滤后符合要求的参数
    //控制器下面方法所要接受参数的
    protected $rules = array(
        'Login' => array(
            'login' => array(
                'code' => ['require', 'alphaDash', 'length' => 32],
            )
        ),
        
        'HandleWx' => array(
            'getwxlist' => array(
                'num' => ['number'],
                'page' => ['number'],
            ),
            'addwx' => array(
                'wx_appid' => ['require', 'alphaDash', 'length' => 18],
                'wx_name' => ['require', 'max' => 32, 'min' => 2],
                'wx_digest' => ['require']
            ),
            'updatewx' => array(
                'wx_id' => ['require', 'number']
            ),
            'deletewx' => array(
                'wx_id' => ['require', 'number']
            ),
        ),
        'Article' => array(
            'index' => array(
                'num' => ['number'],
                'page' => ['number'],
            ),
            'save' => array(
                'art_wxid' => 'require|number',
                'art_title' => ['require', 'min' => 1],
                'art_digest' => ['require', 'min' => 1]
            ),
            'update' => array(
                'art_id' => ['require', 'number'],
                'art_wxid' => ['require', 'number'],
            ),
            'delete' => array(
                'art_id' => ['require', 'number']
            ),
        ),
        'Message' => array(
            'getmsg' => array(
                'm_artid' => 'require|number',
                'num' => ['number'],
                'page' => ['number'],
            ),
            'save' => array(
                'art_wxid' => 'require|number',
                'art_title' => ['require', 'min' => 1],
                'art_digest' => ['require', 'min' => 1]
            ),
            'update' => array(
                'art_id' => ['require', 'number'],
                'art_wxid' => ['require', 'number'],
            ),
            'delete' => array(
                'art_id' => ['require', 'number']
            ),
        ),
    );
    protected function _initialize()
    {
        parent::_initialize();
        $this->req = Request::instance();
        
        // 验证参数,返回成功过滤后的参数数组
        // dump($this->req->header());


        $this->params = $this->checkParams($this->req->param(true));
    }


    //检测客户端传递过来的其他参数（用户名，其他相关）
    /*
    param: $arr [除了time,token以外的其他参数]
    return:     [合格的参数数组]
     */
    protected function checkParams($arr)
    {
        //1.获取验证规则 (Array)
        $rule = $this->rules[$this->req->controller()][$this->req->action()];
        //2. 验证参数并且返回错误
        $this->validater = new Validate($rule);
        if (!$this->validater->check($arr)) {
            return show_msg(0, $this->validater->getError(), '', 400);
        }
        //3. 如果正常，就通过验证
        return $arr;
    }
}
