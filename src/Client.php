<?php


namespace lingyin\taobao;


use lingyin\taobao\request\AbstractRequest;
use lingyin\taobao\response\Response;

class Client
{
    const API_TEST_URL = 'http://gw.api.tbsandbox.com/router/rest';
    const API_URL = 'http://gw.api.taobao.com/router/rest';
    const FORMAT = 'json';

    protected $appKey;
    protected $targetAppKey; // 被调用的目标AppKey，仅当被调用的API为第三方ISV提供时有效
    protected $signMethod = 'md5';
    protected $secretKey;
    protected $apiVersion = '2.0';
    protected $partnerId;

    protected $connectTimeout = 1;
    protected $readTimeout = 2;

    /** 是否打开入参check**/
    protected $checkRequest = true;

    protected $env = 'product';


    public function __construct($appKey, $secretKey)
    {
        $this->appKey = $appKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @param mixed $targetAppKey
     */
    public function setTargetAppKey($targetAppKey)
    {
        $this->targetAppKey = $targetAppKey;
    }

    /**
     * @param mixed $partnerId
     */
    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;
    }

    /**
     * @param int $connectTimeout
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = $connectTimeout;
    }

    /**
     * @param int $readTimeout
     */
    public function setReadTimeout($readTimeout)
    {
        $this->readTimeout = $readTimeout;
    }

    /**
     * @param string $env
     */
    public function setEnv($env)
    {
        $this->env = $env;
    }

    /**
     * @param AbstractRequest $request
     * @param null $session
     * @return Response
     */
    public function execute($request, $session = null)
    {
        //组装系统参数
        $params['app_key'] = $this->appKey;
        $params['v'] = $this->apiVersion;
        $params['format'] = self::FORMAT;
        $params['sign_method'] = $this->signMethod;
        $params['method'] = $apiName = $request->getApiName();
        $params['timestamp'] = date("Y-m-d H:i:s");
        $params['partner_id'] = $this->partnerId;
        if (null != $session) {
            $params['session'] = $session;
        }

        $params = array_merge($request->getParams(), $params);

        //签名
        $params['sign'] = $this->generateSign($params);

        $options = [
            'connect_timeout' => $this->connectTimeout,
            'timeout' => $this->readTimeout,
        ];
        try {
            $client = new \GuzzleHttp\Client($options);
            $params = ['verify' => false, 'form_params' => $params];
            $request = $client->request('POST', $this->getApiUrl(), $params);
            $response = $request->getBody();
            $content = $response->getContents();
            return new Response(\GuzzleHttp\json_decode($content, true), $apiName);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

    }

    protected function generateSign($params)
    {
        ksort($params);
        $stringToBeSigned = $this->secretKey;
        foreach ($params as $k => $v) {
            if ("@" != substr($v, 0, 1)) {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $this->secretKey;
        return strtoupper(md5($stringToBeSigned));
    }

    protected function getApiUrl()
    {
        return 'product' == $this->env ? self::API_URL : self::API_TEST_URL;
    }
}