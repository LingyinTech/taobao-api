<?php


namespace lingyin\taobao;


abstract class AbstractRequest
{
    public function check()
    {
        return true;
    }

    public abstract function getParams();

    public abstract function getApiName();
}