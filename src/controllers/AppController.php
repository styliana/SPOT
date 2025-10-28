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
            // Jeśli szablon nie istnieje (inny niż 404), użyj 404 jako fallback
            if (file_exists($templatePath404)) {
                 ob_start();
                 include $templatePath404;
                 $output = ob_get_clean();
                 // Ustaw kod 404, jeśli renderujemy stronę 404 jako fallback
                 if ($template !== '404') { 
                     http_response_code(404);
                 }
            } else {
                 $output = "Error: Template '$template' not found and 404.html is missing.";
                 http_response_code(404); // Nadal zwracamy 404
            }
        }
        echo $output;
    }

    /**
     * === NOWA METODA ===
     * Publiczna metoda do obsługi błędów 404 Not Found.
     * Ustawia kod odpowiedzi i renderuje widok 404.
     */
    public function notFound(): void
    {
        http_response_code(404);
        $this->render('404'); // Wywołanie chronionej metody render z wnętrza klasy jest OK
    }
}