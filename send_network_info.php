<?php

// 引入配置文件
$config = require 'config.php';

// 引入并实例化 ApiClient 类
require 'handler/ApiClient.php';
$client = new ApiClient($config);

// 获取外网 IP 地址
$externalIp = file_get_contents('https://api.ipify.org');

// 获取网卡信息
$ifconfigOutput = shell_exec('ifconfig -a');

// 准备数据
$data = [
    'ip' => $externalIp,
    'network_info' => $ifconfigOutput, // 包含网卡信息
];

// 发送请求并获取响应
try {
    $response = $client->sendRequest('/updateIp', $data);
    echo 'Response: ' . $response['msg'];
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
