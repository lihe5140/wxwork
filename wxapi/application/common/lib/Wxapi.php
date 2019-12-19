<?php

namespace app\common\lib;

use app\common\lib\Curl;

class Wxapi
{

    private $appid = null;
    private $appsecret = null;
    
    public function __construct()
    {
        // 配置文件wxapi.php(application\extra\wxapi.php)中定义access_token_api
        $this->appid = config('wxapi.appid');
        $this->appsecret = config('wxapi.appsecret');
    }
    /**
     * 获取access_token
     * 
     * @param String $AppId 
     * @param String $AppSecret
     * @return String
     */
    public function GetOpenId($code)
    {
        
        // 接口地址
        $url="https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->appsecret}&js_code={$code}&grant_type=authorization_code";
        $curl = new Curl();
        $returnData = $curl::curl_get_https($url);
        // $returnData = json_decode($returnData, true);
        return  $returnData;
    }
    
    
}
