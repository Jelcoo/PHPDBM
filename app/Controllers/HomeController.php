<?php

namespace App\Controllers;

class HomeController extends Controller {
    public function index()
    {
        return $this->pageLoader->setPage('home')->render(['user' => 'John']);
    }
}
