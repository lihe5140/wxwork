<?php



return [
    'appid' => 'wx7ee6285fafc10a0a', //aes加密的密钥  客户端和服务端一致 不能大于16位
    'appsecret' => 'c45b368918c1ab4db24a8d2ff51102de', //aes加密的向量  客户端和服务端一致 16位

    //app_type配置数组
    'app_types' => [
        'ios', 'android'
    ],
    'app_sign_time' => 10, //sign失效时间 秒  测试的时候 设置长一些
    'app_sign_cache_time' => 20, //sign缓存的有效时间
];
