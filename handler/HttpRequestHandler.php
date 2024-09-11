<?php

class HttpRequestHandler
{
    protected $data;
    protected $response;

    public function __construct()
    {
        $this->response = [
            'status' => 'error',
            'message' => 'Invalid request',
        ];

        // 确保请求方法是POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->data = json_decode(file_get_contents('php://input'), true);
            $this->response['status'] = 'success';
        } else {
            http_response_code(405);  // Method Not Allowed
            $this->response['message'] = 'Only POST method is allowed';
        }
    }

    protected function process()
    {
        // 具体的处理逻辑在子类中实现
    }

    public function sendResponse()
    {
        header('Content-Type: application/json');
        echo json_encode($this->response);
    }
}
