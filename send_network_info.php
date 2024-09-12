<?php

// 引入配置文件
$config = require 'config.php';

// 引入并实例化 ApiClient 类
require 'handler/ApiClient.php';
$client = new ApiClient($config);

// 获取网卡信息
$output = shell_exec('ifconfig -a');

// 2. 将 ifconfig 输出格式化为数组
// 通常需要解析 ifconfig 输出，转换为你需要的格式
$network_data = [];
$interfaces = preg_split('/\n\s*\n/', trim($output)); // 根据空行拆分每个接口
foreach ($interfaces as $interface) {
    $lines = explode("\n", trim($interface));
    $iface_name = strtok($lines[0], ' ');
    $network_data[$iface_name] = $interface;
}

// 准备数据
$data = [
    'network_info' => $network_data, // 包含网卡信息
];

// 发送请求并获取响应
try {
    $response = $client->sendRequest('/netInfo', $data);
    echo 'Response: ' . $response['msg'];
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
