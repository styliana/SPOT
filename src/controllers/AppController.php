<?php
require_once __DIR__ . '/../repository/BookingRepository.php'; 

class AppController {
    private $request;
    private $bookingRepository;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];

        $this->bookingRepository = new BookingRepository(); 

        if (!isset($_SESSION['last_cleanup_time']) || $_SESSION['last_cleanup_time'] < time() - 600) {
             $this->bookingRepository->cleanArchivedBookings();
             $_SESSION['last_cleanup_time'] = time();
        }
    }

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }

    protected function redirect(string $url): void
    {
        if ($url !== '' && $url[0] !== '/') {
            $url = '/' . $url;
        }

        if (headers_sent()) {
            echo "<script>window.location.href='$url';</script>";
        } else {
            header("Location: $url");
        }
        exit(); 
    }

    protected function requireLogin(): void
    {
        if (!isset($_SESSION['user_email'])) {
            $this->redirect('/login');
        }
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

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('404');
    }
    

    public function forbidden(): void
    {
        http_response_code(403);
        $this->render('403'); 
    }
    
    public function badRequest(): void
    {
        http_response_code(400);
        $this->render('400');
    }
}