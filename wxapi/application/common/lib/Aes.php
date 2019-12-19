<?php

namespace app\common\lib;
use think\Exception;
/**
 * aes 加密 解密类库
 * @by singwa
 * Class Aes
 * @package app\common\lib
 */
class Aes
{
    private $key = null;
    private $iv = null;
    /**
     *
     * @param $key 		密钥
     * @param $iv       向量    
     * @return String
     */
    public function __construct()
    {
        // 配置文件app.php(application\extra\app.php)中定义aeskey
        $this->key = config('aes.aeskey');
        $this->iv = config('aes.aesiv');
    }
    /**
     * php7.1.0 版本以下 加密  加密模式也要一致 pkcs5
     * @param String input 加密的字符串
     * @param String key   解密的key
     * @param String iv    解密的iv
     * @return HexString
     */
    public function encrypt($input = '')
    {
        if (version_compare(PHP_VERSION, '7.1.0', '<')) {
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $this->iv);
            mcrypt_generic_init($module, $this->key, $this->iv);
            $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $input = $this->pkcs5_pad($input, $size);
            $data = mcrypt_generic($module, $input);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);
            $data = base64_encode($data); //16进制
            return $data;
        }else{
            return base64_encode(openssl_encrypt($input, 'aes-128-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv));
            
        }
    }
    /**
     * 填充方式 pkcs5
     * @param String text 		 原始字符串
     * @param String blocksize   加密长度
     * @return String
     */
    private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * php7.1.0 版本以下 解密
     * @param String input 解密的字符串
     * @param String key   解密的key
     * @param String iv    解密的iv
     * @return String
     */
    public function decrypt($sStr)
    {
        if (version_compare(PHP_VERSION, '7.1.0', '<')) {
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $this->iv);
            mcrypt_generic_init($module, $this->key, $this->iv);
            $encryptedData = base64_decode($sStr);
            $encryptedData = mdecrypt_generic($module, $encryptedData);
            $dec_s = strlen($encryptedData);
            $padding = ord($encryptedData[$dec_s - 1]);
            $decrypted = substr($encryptedData, 0, -$padding);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);
            if (!$decrypted) {
                throw new Exception("Decrypt Error,Please Check SecretKey");
            }
            return $decrypted;
        } else {
            return openssl_decrypt(base64_decode($sStr), 'aes-128-cbc', $this->key, true, $this->iv);
        }
    }
}

