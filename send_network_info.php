<?php

// 引入配置文件
$config = require 'config.php';

// 引入并实例化 ApiClient 类
require 'handler/ApiClient.php';
$client = new ApiClient($config);

// 获取网卡信息
$output = shell_exec('ifconfig -a');

// 解析 ifconfig 输出，将每个网卡的信息转换为结构化数组
$network_data = [];
$interfaces = preg_split('/\n\s*\n/', trim($output)); // 根据空行拆分每个接口

foreach ($interfaces as $interface) {
    $lines = explode("\n", trim($interface));
    $iface_name = strtok($lines[0], ' '); // 获取网卡名称

    // 添加 netName 字段
    $interface_data = [
        'netName' => $iface_name, // 添加网卡名称作为字段
        'mac_address' => null,
        'inet_addr' => null,
        'inet6_addr' => [],
        'mtu' => null,
        'rx_packets' => null,
        'tx_packets' => null,
        'rx_bytes' => null,
        'tx_bytes' => null,
    ];

    foreach ($lines as $line) {
        if (preg_match('/HWaddr\s+([0-9A-Fa-f:]+)/', $line, $matches)) {
            $interface_data['mac_address'] = $matches[1];
        }
        if (preg_match('/inet addr:([\d\.]+)/', $line, $matches)) {
            $interface_data['inet_addr'] = $matches[1];
        }
        if (preg_match('/inet6 addr:\s*([a-fA-F0-9:\/]+)/', $line, $matches)) {
            $interface_data['inet6_addr'][] = $matches[1];
        }
        if (preg_match('/MTU:(\d+)/', $line, $matches)) {
            $interface_data['mtu'] = $matches[1];
        }
        if (preg_match('/RX packets:(\d+)/', $line, $matches)) {
            $interface_data['rx_packets'] = $matches[1];
        }
        if (preg_match('/TX packets:(\d+)/', $line, $matches)) {
            $interface_data['tx_packets'] = $matches[1];
        }
        if (preg_match('/RX bytes:(\d+)/', $line, $matches)) {
            $interface_data['rx_bytes'] = $matches[1];
        }
        if (preg_match('/TX bytes:(\d+)/', $line, $matches)) {
            $interface_data['tx_bytes'] = $matches[1];
        }
    }

    // 将每个网卡的数据加入数组
    $network_data[] = $interface_data;
}

// 发送请求并获取响应
try {
    $response = $client->sendRequest('/netInfo', $network_data);
    echo 'Response: ' . $response['msg'];
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
