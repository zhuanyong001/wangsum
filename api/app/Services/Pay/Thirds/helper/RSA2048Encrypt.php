<?php

namespace App\Services\Pay\Thirds\helper;

class RSA2048Encrypt
{
    /**
     * RSA最大加密明文大小
     */
    private const MAX_ENCRYPT_BLOCK = 245;
    /**
     * RSA最大解密密文大小
     */
    private const MAX_DECRYPT_BLOCK = 256;

    private const ALGORITHM_NAME = 'RSA';
    private const RSA_ALGORITHM = 'RSA/ECB/PKCS1Padding';
    private const MD5_RSA = 'MD5withRSA';

    public static function main()
    {
        //return;
        try {
            // 生成密钥对
            $keyPairMap = self::getKeyPairMap();
            $privateKey = $keyPairMap['privateKey'];
            $publicKey = $keyPairMap['publicKey'];
            echo "私钥 => $privateKey\n";
            echo "公钥 => $publicKey\n";
            var_dump(self::getPublicKey($publicKey));
            echo "\n";
            echo  self::getPrivateKey($privateKey);
            echo "\n";
            // RSA加密
            $data = '123456';
            $encryptData = self::encrypt($data, self::getPublicKey($publicKey));

            echo "加密后内容 => $encryptData\n";

            // RSA解密
            $decryptData = self::decrypt($encryptData, self::getPrivateKey($privateKey));
            echo "解密后内容 => $decryptData\n";

            // RSA签名
            $sign = self::sign($data, self::getPrivateKey($privateKey));
            // RSA验签
            $result = self::verify($data, self::getPublicKey($publicKey), $sign);
            echo "验签结果 => $result\n";
        } catch (\Exception $e) {
            echo "RSA加解密异常\n";
            echo $e->getMessage();
        }
    }

    /**
     * 获取密钥对
     */
    public static function getKeyPairMap()
    {
        $config = array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)['key'];
        $keyPairMap = array(
            "privateKey" => base64_encode($privateKey),
            "publicKey" => base64_encode($publicKey)
        );
        return $keyPairMap;
    }

    /**
     * 获取公钥
     *
     * @param $publicKey base64加密的公钥字符串
     * @return false|resource
     */
    public static function getPublicKey($publicKey)
    {
        $key = base64_decode($publicKey);

        //$key = openssl_pkey_get_public($publicKey);
        return $key;
    }

    /**
     * 获取私钥
     *
     * @param $privateKey base64加密的私钥字符串
     * @return false|resource
     */
    public static function getPrivateKey($privateKey)
    {
        $privateKey = base64_decode($privateKey);
        //$key = openssl_pkey_get_private($privateKey);
        return $privateKey;
    }

    /**
     * RSA加密
     *
     * @param string $data 待加密数据
     * @param resource $publicKey 公钥
     * @param int $chunkSize 分段大小
     * @return string
     */
    public static function encrypt($data, $publicKey, $chunkSize = self::MAX_ENCRYPT_BLOCK)
    {
        $encrypted = '';
        $data = str_split($data, $chunkSize);
        foreach ($data as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, $publicKey);
            $encrypted .= $encryptData;
        }
        return base64_encode($encrypted);
    }




    /**
     * RSA解密
     *
     * @param string $data 待解密数据
     * @param resource $privateKey 私钥
     * @param int $chunkSize 分段大小
     * @return string
     */
    public static function decrypt($data, $privateKey, $chunkSize = self::MAX_DECRYPT_BLOCK)
    {
        $decrypted = '';
        $data = str_split(base64_decode($data), $chunkSize);
        foreach ($data as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $privateKey);
            $decrypted .= $decryptData;
        }
        return $decrypted;
    }


    /**
     * 签名
     *
     * @param $data 待签名数据
     * @param $privateKey 私钥
     * @return string
     */
    public static function sign($data, $privateKey)
    {
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_MD5);
        return base64_encode($signature);
    }

    /**
     * 验签
     *
     * @param $srcData 原始字符串
     * @param $publicKey 公钥
     * @param $sign 签名
     * @return bool
     */
    public static function verify($srcData, $publicKey, $sign)
    {
        return openssl_verify($srcData, base64_decode($sign), $publicKey, OPENSSL_ALGO_MD5) === 1;
    }
}
