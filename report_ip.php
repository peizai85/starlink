<?php

// 引入配置文件
$config = require 'config.php';

// 引入并实例化ApiClient类
require 'handler/ApiClient.php';
$client = new ApiClient($config);

// 获取外网IP地址
$externalIp = file_get_contents('https://api.ipify.org');

// 准备数据
$data = [
    'ip' => $externalIp,
];

// 发送请求并获取响应
try {
    $response = $client->sendRequest('/updateIp', $data);
    echo 'Response: ' . $response['msg'];
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
