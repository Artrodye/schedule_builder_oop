<?php

namespace app\views;

use app\http\JsonResponse;
use app\http\Request;
use Twig\Extra\CssInliner\CssInlinerExtension;

class TemplateWriter
{
    private $twig = null;
    public function createTwig(): void
    {
        $loader = new \Twig\Loader\FilesystemLoader( __DIR__ . '/templates');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => '/compilation_cache',
            'auto_reload' => true
        ]);
        $this->twig->addExtension(new CssInlinerExtension());
    }
    public function write(Request $request, JsonResponse $response)
    {
        header('Content-Type: Application/json');
        $httpResponseCode = $response->getCode();
        http_response_code($httpResponseCode);
        if ($response->getCode() !== 200) {
            echo json_encode($response->getResult());
            exit();
        }
        if (is_null($this->twig)) {
            $this->createTwig();
        }
        $template = $this->twig->load($request->getQueryValue('method') . '.html');
        echo $template->render([
            'groupName' => $request->getBodyValue('id'),
            'events' => $response->getResult()['rows']
        ]);
    }
}