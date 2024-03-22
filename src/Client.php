<?php

namespace Aliyuncaptcha;

use afs\Request\V20180112\AuthenticateSigRequest;

require_once __DIR__ . '/../core/aliyun-php-sdk-core/Config.php';

class Client
{

    protected $appKey;

    protected $accessKey;

    protected $accessSecret;

    public function __construct($appKey = null, $accessKey = null, $accessSecret = null)
    {
        $this->appKey       = $appKey;
        $this->accessKey    = $accessKey;
        $this->accessSecret = $accessSecret;
    }

    public function verify($sessionId, $token, $sig, $scene, $ip)
    {
        $iClientProfile = \DefaultProfile::getProfile("cn-qingdao", $this->accessKey, $this->accessSecret);
        $client         = new \DefaultAcsClient($iClientProfile);
        \DefaultProfile::addEndpoint("cn-qingdao", "cn-qingdao", "afs", "afs.aliyuncs.com");

        $request = new AuthenticateSigRequest();
        $request->setSessionId($sessionId);// 必填参数，从前端获取，不可更改，android和ios只传这个参数即可
        $request->setToken($token);        // 必填参数，从前端获取，不可更改
        $request->setSig($sig);            // 必填参数，从前端获取，不可更改
        $request->setScene($scene);        // 必填参数，从前端获取，不可更改
        $request->setAppKey($this->appKey);//必填参数，后端填写
        $request->setRemoteIp($ip);        //必填参数，后端填写

        try {

            $response = $client->getAcsResponse($request);//返回code 100表示验签通过，900表示验签失败
            return get_object_vars($response);

        }catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}