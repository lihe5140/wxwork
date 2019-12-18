<?php

namespace app\api\controller;

use app\common\lib\Aes;

class AesEncrypt extends Common
{
    /**
     * 更新公众号接口数据到数据库
     *
     * @param [string] $type  
     * @param [int] $offset
     * @param [int] $count
     * @return void
     */
    public function index()
    {
        $aes = new Aes();
        return show_msg(1, '加密成功', $aes->decrypt("e8d9fc3d16625d05142841524330b5f89f22f207ec59c4a7d583b8fb10d50ba8"), 200);
    }
}
