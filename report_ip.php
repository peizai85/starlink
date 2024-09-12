<?php

// 引入配置文件
$config = require 'config.php';

// 引入并实例化ApiClient类
require 'handler/ApiClient.php';
$client = new ApiClient($config);

// 定义多个外部IP获取服务
$ipSources = [
    'http://ipinfo.io/ip',
    'http://api.ipify.org',
    'http://checkip.amazonaws.com',
    'http://ifconfig.me/ip',
];

// 设置获取外部IP的超时时间
$context = stream_context_create([
    'http' => [
        'timeout' => 5, // 设置超时时间为 5 秒
    ]
]);

// 重试机制获取外部IP
$retry = count($ipSources); // 使用与 IP 服务数量相等的重试次数
$externalIp = false;

foreach ($ipSources as $source) {
    try {
        // 使用不同的服务获取外部 IP
        $externalIp = @file_get_contents($source, false, $context);
        if ($externalIp !== false) {
            echo "IP fetched successfully from: $source\n";
            break; // 成功获取到 IP 后退出循环
        } else {
            echo "Failed to fetch IP from: $source\n";
        }
    } catch (Exception $e) {
        error_log("Error fetching IP from $source: " . $e->getMessage());
    }
    sleep(1); // 等待1秒后再尝试下一个服务
}

if ($externalIp === false) {
    throw new Exception("Failed to fetch external IP from all sources.");
}

// 准备数据
$data = [
    'ip' => trim($externalIp), // trim 去掉多余的空白字符
];

// 发送请求并获取响应
try {
    $response = $client->sendRequest('/updateIp', $data);
    echo 'Response: ' . $response['msg'];
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
