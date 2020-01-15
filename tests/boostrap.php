<?php

require __DIR__ . '/../vendor/autoload.php';


$client = new \lingyin\taobao\Client('test','test');
$client->setEnv('sandbox');
$client->setPartnerId('top-sdk-php-20200115');
$client->setConnectTimeout(5);
$client->setReadTimeout(10);

$request = new \lingyin\taobao\request\trade\TradeGetRequest();
$request->setTid('123456789');
$result = $client->execute($request,'test');
print_r($result);
