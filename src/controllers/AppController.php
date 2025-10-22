<?php


class AppController {
    private $request;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
    }

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }

    protected function render(string $template = null, array $variables = [])
    {
        // Sprawdzamy najpierw plik .php, potem .html
        $templatePathPhp = 'public/views/'. $template.'.php';
        $templatePathHtml = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html'; // Nadal używamy 404.html

        $output = "";

        // Wybór ścieżki: preferujemy .php
        $chosenTemplatePath = null;
        if (file_exists($templatePathPhp)) {
            $chosenTemplatePath = $templatePathPhp;
        } elseif (file_exists($templatePathHtml)) {
            $chosenTemplatePath = $templatePathHtml;
        }


        if($chosenTemplatePath){
            extract($variables);

            ob_start();
            include $chosenTemplatePath;
            $output = ob_get_clean();
        } else {
            // Jeśli nie znaleziono ani .php ani .html, renderujemy 404
            ob_start();
            include $templatePath404;
            $output = ob_get_clean();
        }
        echo $output;
    }

}