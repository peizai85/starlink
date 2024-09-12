<?php

class ApiClient
{
    protected $apiUrl;
    protected $token;
    protected $devicesId;

    public function __construct($config)
    {
        $this->apiUrl = $config['api_base_url'];
        $this->token = $config['api_key'];
        $this->devicesId = $config['devices_id'];
    }

    public function sendRequest($endpoint, $data = [])
    {
        $url = $this->apiUrl . $endpoint;

        // 添加通用参数
        $data['token'] = $this->token;
        $data['devicesId'] = $this->devicesId;

        // 转换为JSON格式
        $jsonData = json_encode($data);
        print($jsonData);

        // 初始化cURL
        $ch = curl_init($url);
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
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        // 解析JSON响应
        $decodedResponse = json_decode($response, true);

        // 关闭cURL会话
        curl_close($ch);

        // 检查API响应的code字段
        if ($decodedResponse['code'] !== 20000) {
            throw new Exception('API Error: ' . $decodedResponse['msg']);
        }

        return $decodedResponse;
    }
}
