<?php

use app\Container\Container;
use app\http\ResponseWriter;

require __DIR__ . '/bootstrap.php';

$request = \app\http\Request::createFromGlobal();
$response = ((new Container())->get(\app\routerProvider\Router::class))->handle($request);
((new Container())->get(ResponseWriter::class))->write($response);