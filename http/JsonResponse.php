<?php

namespace app\http;

use app\ApplicationException\ApplicationException;

class JsonResponse
{

    public function __construct(
        private readonly int $responseCode,
        private readonly mixed $responseResult
    ) {}

    public function getCode(): int
    {
        return $this->responseCode;
    }

    public function getResult(): mixed
    {
        return $this->responseResult;
    }
}