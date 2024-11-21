<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function index(): string
    {
        $username = $_SESSION['username'];

        return $this->pageLoader->setPage('home')->render(['user' => $username]);
    }
}
