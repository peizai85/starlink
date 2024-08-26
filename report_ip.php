<?php

// 引入配置文件
$config = require 'config.php';

// 获取外网IP地址
$externalIp = file_get_contents('https://api.ipify.org');

// 拼接完整的API URL
$apiUrl = $config['api_base_url'] . '/updateIp';

// 准备上报数据
$data = [
    'ip' => $externalIp,
    'api_key' => $config['api_key'],
];

// 将数据转换为JSON格式
$jsonData = json_encode($data);

// 初始化cURL
$ch = curl_init($apiUrl);

// 设置cURL选项
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData),
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

// 执行请求并获取响应
$response = curl_exec($ch);

// 检查错误
if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

// 关闭cURL会话
curl_close($ch);
