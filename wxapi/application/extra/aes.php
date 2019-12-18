<?php


return [
    'pass_pre' => 'zzs',//密码加密
    'aeskey'=>'XIOW8eRMmiPldX73',//aes加密的密钥  客户端和服务端一致 不能大于16位
    'aesiv'=>'0000000000000000',//aes加密的向量  客户端和服务端一致 16位

    //app_type配置数组
    'app_types'=>[
        'ios','android'
    ],
    'app_sign_time'=>10,//sign失效时间 秒  测试的时候 设置长一些
    'app_sign_cache_time'=>20,//sign缓存的有效时间
];
