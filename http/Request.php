<?php

namespace app\http;

readonly class Request
{
    public function __construct(
        private array $query,
        private array $body
    ) {}


    public static function createFromGlobal(): Request
    {
        $json = json_decode(file_get_contents('php://input'), true) ?: [];

        return new Request($_GET, array_merge($_POST, $json));
    }

    public function getQueryValue(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function getBodyValue(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function unwrapBody(): array
    {
        return $this->body;
    }
}