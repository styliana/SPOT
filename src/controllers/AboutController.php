<?php

require_once 'AppController.php';

class AboutController extends AppController {

    public function about() {
        return $this->render('about');
    }
}