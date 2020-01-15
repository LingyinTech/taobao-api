<?php


namespace lingyin\taobao\response;


class Response
{

    protected $code;
    protected $msg;
    protected $sub_code;
    protected $sub_msg;
    protected $request_id;

    protected $response;

    /**
     * Response constructor.
     * @param array $response
     * @param string $apiName
     */
    public function __construct($response, $apiName)
    {
        foreach (['code', 'msg', 'sub_code', 'sub_msg','request_id'] as $value) {
            if (isset($response['error_response'][$value])) {
                $this->{$value} = $response['error_response'][$value];
            }
        }

        $node = $this->getNodeByApiName($apiName);
        if (isset($response[$node])) {
            $className = $this->getClassByApiName($apiName);
            $this->response = new $className($response[$node]);
        }
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @return int
     */
    public function getSubCode()
    {
        return $this->sub_code;
    }

    /**
     * @return string
     */
    public function getSubMsg()
    {
        return $this->sub_msg;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->request_id;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    protected function getClassByApiName($apiName)
    {
        return '\\lingyin\\' . implode('\\', explode('.', $apiName));
    }

    protected function getNodeByApiName($apiName)
    {
        return implode('_', explode('.', $apiName));
    }
}