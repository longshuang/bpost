<?php
/**
 * 模拟发送http 请求
 * User: zhangsiwei
 * Date: 2018/8/20
 * Time: 15:29
 */

namespace nlebpost\Bpost;

use wappr\logger;

class HttpClient
{
    private static $ch;

    /**
     * 设置基础信息
     * @param $url
     * @param array $header
     * @param bool $proxy
     * @param int $expire
     */
    private static function init($url, $header = [], $proxy = false, $expire = 36000)
    {
        self::$ch = curl_init();
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        // 设置代理
        if (!$proxy) {
            curl_setopt(self::$ch, CURLOPT_PROXY, $proxy);
        }
        // 设置SSL
        $isSSL = substr($url, 0, 8) == 'https://' ? true : false;
        if ($isSSL) {
            curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
            curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }
        // 设置请求header
        if (!empty($header)) {
            curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt(self::$ch, CURLOPT_FOLLOWLOCATION, true);   // 使用自动跳转
        //下面发送一个常规的POST请求，类型为application/x-www-form-urlencoded,就像提交表单一样
        curl_setopt(self::$ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.2; rv:19.0) Gecko/20100101 Firefox/19.0");
        curl_setopt(self::$ch, CURLOPT_HEADER, false);
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);   // 结果集的形式返回
        curl_setopt(self::$ch, CURLOPT_TIMEOUT, $expire); // 设置cURL允许执行的最长秒数。
    }


    /**
     * post 请求
     * @param string $url 发送地址
     * @param array|string $data 发送报文
     * @param array $header 发送头
     * @param bool $proxy 代理信息
     * @param int $expire 超时时间
     * @return bool|mixed
     * @throws \Exception
     */
    public static function post($url, $data = [], $header = [], $proxy = false, $expire = 36000)
    {
        self::init($url, $header, $proxy, $expire);
        // POST发送数据
        curl_setopt(self::$ch, CURLOPT_POST, true);//发送一个常规的Post请求
        curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $data);//Post提交的数据包
        $response = false;
        try {
            // 执行发送CURL
            $response = curl_exec(self::$ch);
            if (curl_errno(self::$ch)) {
                $curlError = curl_error(self::$ch);
                throw new \Exception($curlError);
            }
            $httpCode = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
            if ($httpCode != 200) {
                throw new \Exception("httpCode=##{$httpCode}");
            }
            curl_close(self::$ch);
        } catch (\Exception $e) {
            $message = "code:{$e->getCode()}, message:{$e->getMessage()} file:{$e->getFile()} line:{$e->getLine()}";
            throw new \Exception($message);
        }
        return $response;
    }

    /**
     * BPost API请求
     * @param $config
     * @param $data
     * @return array|string
     * @throws \Exception
     */
    public static function sendXMLBPostShipAPIRequest($config, $data)
    {
        $header = ['Content-Type:text/xml; charset=utf-8'];
        $data = array_merge($data, $config);
        $response = null;
        try {
            $xmlParams = XmlBPost::createShipXml($data);
            $response = self::post($config['ship_url'], $xmlParams, $header);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
        return $response;
    }
}