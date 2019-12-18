<?php

namespace app\common\lib;

use app\common\lib\Curl;

class Wxapi
{

    private $access_token_api = null;
    
    public function __construct()
    {
        // 配置文件wxapi.php(application\extra\wxapi.php)中定义access_token_api
        $this->access_token_api = config('wxapi.access_token_api');
    }
    /**
     * 获取access_token
     * 
     * @param String $AppId 
     * @param String $AppSecret
     * @return String
     */
    public function GetToken($AppId, $AppSecret,$code)
    {
        // 接口地址
        $url = $this->access_token_api . "&appid={$AppId}&secret={$AppSecret}";
        $curl = new Curl();
        $returnData = $curl::curl_get_https($url);
        $returnData = json_decode($returnData, true);
        $access_token=$returnData['access_token'];
        return  $access_token;
    }
    
    
}
