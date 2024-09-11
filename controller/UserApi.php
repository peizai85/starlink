<?php

require 'handler/HttpRequestHandler.php';

class UserApi extends HttpRequestHandler
{
    public function __construct()
    {
        parent::__construct();
        $this->process();
        $this->sendResponse();
    }

    protected function process()
    {
        if (isset($this->data['username'])) {
            // 模拟业务逻辑处理
            $this->response['message'] = 'User ' . $this->data['username'] . ' processed successfully';
        } else {
            $this->response['status'] = 'error';
            $this->response['message'] = 'Username not provided';
        }
    }
}

// 创建实例处理请求
$api = new UserApi();
