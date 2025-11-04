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
        $templatePathPhp = 'public/views/'. $template.'.php';
        $templatePathHtml = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';

        $output = "";
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
            if (file_exists($templatePath404)) {
                 ob_start();
                 include $templatePath404;
                 $output = ob_get_clean();
                 if ($template !== '404') { 
                     http_response_code(404);
                 }
            } else {
                 $output = "Error: Template '$template' not found and 404.html is missing.";
                 http_response_code(404);
            }
        }
        echo $output;
    }

    /**
     * Strona 404 Not Found
     */
    public function notFound(): void
    {
        http_response_code(404);
        $this->render('404');
    }
    
    /**
     * === NOWA METODA 403 ===
     * Dostęp zabroniony (użytkownik jest zalogowany, ale nie ma uprawnień)
     */
    public function forbidden(): void
    {
        http_response_code(403);
        $this->render('403'); // Renderuje 403.html
    }
    
    /**
     * === NOWA METODA 400 ===
     * Nieprawidłowe żądanie (np. źle wypełniony formularz)
     */
    public function badRequest(): void
    {
        http_response_code(400);
        $this->render('400'); // Renderuje 400.html
    }
}