<?php

namespace App\Controllers;

class LoginController extends Controller {
    public function index()
    {
        return $this->pageLoader->setPage('login')->render();
    }
}
