<?php

// Wymagamy repozytorium do czyszczenia archiwalnych rezerwacji
require_once __DIR__ . '/../repository/BookingRepository.php'; 

class AppController {
    private $request;
    private $bookingRepository;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
        
        // Inicjalizacja repozytorium
        $this->bookingRepository = new BookingRepository(); 

        // === LOGIKA CZYSZCZENIA ARCHIWALNYCH REZERWACJI ===
        // Uruchamiamy czyszczenie tylko raz na 10 minut (600 sekund) na sesję.
        if (!isset($_SESSION['last_cleanup_time']) || $_SESSION['last_cleanup_time'] < time() - 600) {
             $this->bookingRepository->cleanArchivedBookings();
             $_SESSION['last_cleanup_time'] = time();
        }
        // ==================================================
    }

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }

    /**
     * === ZAKTUALIZOWANA METODA: Przekierowanie ===
     * Czyni ją odporną na błędy "headers already sent".
     */
    protected function redirect(string $url): void
    {
        // Upewniamy się, że URL jest poprawny (zaczyna się od /)
        if ($url !== '' && $url[0] !== '/') {
            $url = '/' . $url;
        }

        // Sprawdzamy, czy nagłówki zostały już wysłane
        if (headers_sent()) {
            // Jeśli tak, zróbmy fallback na JavaScript (brzydkie, ale działa)
            echo "<script>window.location.href='$url';</script>";
        } else {
            // Jeśli nie, wyślij normalny nagłGłówek
            header("Location: $url");
        }
        exit(); // Zawsze kończymy skrypt po przekierowaniu
    }

    /**
     * === NOWA METODA: Wymaganie logowania ===
     * Sprawdza, czy użytkownik jest zalogowany. Jeśli nie, przekierowuje do /login.
     */
    protected function requireLogin(): void
    {
        // Sprawdzamy, czy w sesji istnieje klucz 'user_email' (ustawimy go podczas logowania)
        if (!isset($_SESSION['user_email'])) {
            $this->redirect('/login');
        }
        // Jeśli istnieje, skrypt po prostu idzie dalej.
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