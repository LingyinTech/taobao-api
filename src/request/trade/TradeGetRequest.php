<?php


namespace lingyin\taobao\request\trade;


use lingyin\taobao\request\AbstractRequest;

class TradeGetRequest extends AbstractRequest
{

    protected $fields = 'tid,orders';
    protected $tid;

    /**
     * @param string $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param mixed $tid
     */
    public function setTid($tid)
    {
        $this->tid = $tid;
    }

    public function getParams()
    {
        return [
            'tid' => $this->tid,
            'fields' => $this->fields,
        ];
    }

    public function getApiName()
    {
        return 'taobao.trade.get';
    }
}