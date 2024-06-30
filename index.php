<?php

use app\Container\Container;
use app\http\ResponseWriter;
use app\views\TemplateWriter;

require __DIR__ . '/bootstrap.php';

$request = \app\http\Request::createFromGlobal();
$response = ((new Container())->get(\app\routerProvider\Router::class))->handle($request);
// В реализации TemplateWriter::write я допустил создание template для всех запросов, так что от этого if можно избавиться
// и все результаты выводить через TemplateWriter, но тк это касалось только запроса на получение событий для группы, я этого не делал.
// При необходимости исправлю.
if ($request->getQueryValue('method') === 'getEventsForGroup') {
    ((new Container())->get(TemplateWriter::class))->write($request, $response);
} else {
    ((new Container())->get(ResponseWriter::class))->write($response);
}