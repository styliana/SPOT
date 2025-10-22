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
        $templatePath = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';
        $output = "";
                 
        if(file_exists($templatePath)){
            extract($variables);
            
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        } else {
            ob_start();
            include $templatePath404;
            $output = ob_get_clean();
        }
        echo $output;
    }

}