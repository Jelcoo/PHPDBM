<?php

namespace App\Controllers;

class ErrorController extends Controller
{
    public function error404(): string
    {
        return $this->pageLoader->setPage('_404')->render();
    }
}
