<?php

namespace app\http;

use app\http\JsonResponse;

class ResponseWriter
{
    public function write(JsonResponse $response): void
    {
        header('Content-Type: Application/json');
        $httpResponseCode = $response->getCode();
        http_response_code($httpResponseCode);
        echo json_encode($response->getResult());
    }
}