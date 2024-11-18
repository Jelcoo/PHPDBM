<?php

namespace App\Controllers;

class LoginController extends Controller
{
    public function index(): string
    {
        return $this->pageLoader->setPage('login')->render();
    }

    public function login(): string
    {
        return '';
    }
}
