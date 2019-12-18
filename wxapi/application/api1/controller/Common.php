<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Request;
use app\common\lib\Curl;


class Common extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
        // $this->getAccessToken();
    }
    
    // 获取AccessToken  
    // public function getAccessToken()
    // {
    //     //AppID
    //     $appid = "wx837868c37dd828de";
    //     // AppSecret     
    //     $appSecret = "ca3b63d400f796e20387a34e608bf25c";
    //     // 获取AccessToken Api
    //     $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appSecret}";
    //     $res = db('token')->where('g_id','wx837868c37dd828de')->find();
    //     if (time() - intval($res['last_create_time']) > 7200) {
    //         $curl = new Curl();
    //         $returnData =$curl::curl_get_https($url);
    //         $returnData = json_decode($returnData, true);
    //         // var_dump($returnData);
    //         if (in_array('errcode', $returnData)) {
    //             // 发生错误              
    //             return false;
    //         } else {
    //             $access_token = $returnData['access_token'];
    //             $data['access_token'] = $access_token;
    //             $data['last_create_time'] = time();
    //             $result = db('token')->where('g_id','wx837868c37dd828de')->update($data);
    //             // var_dump($access_token);
    //             if ($result) {
    //                 $access_token = $access_token;
    //             }
    //         }
    //     } else {
    //         $access_token = $res['access_token'];
    //     }
    //     return $access_token;
    // }
    /* PHP CURL HTTPS GET */
    public static function curl_get_https($url)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
    }
    /* PHP CURL HTTPS POST */
    public static function curl_post_https($url, $data)
    { // 模拟提交数据函数
        $headerArray = array(
            "Content-type:application/json;charset='utf-8'",
            'Content-Length: ' . strlen($data),
            'X-AjaxPro-Method:ShowList',
            // "Accept: application/json"
        );
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl); //捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据，json格式
    }
}
