<?php

namespace app\views;

use Twig\Extra\CssInliner\CssInlinerExtension;

class TemplateWriter
{
    private $twig = null;
    public function createTwig(): void
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => '/compilation_cache',
            'auto_reload' => true
        ]);
        $this->twig->addExtension(new CssInlinerExtension());
    }
    public function load(string $templateName)
    {
        if (is_null($this->twig)) {
            $this->createTwig();
        }
        return $this->twig->load($templateName . '.html');
    }
}