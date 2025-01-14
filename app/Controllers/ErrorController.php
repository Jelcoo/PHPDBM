<?php

namespace App\Controllers;

class ErrorController extends Controller
{
    public function error404(): string
    {
        return $this->pageLoader->setPage('_404')->render();
    }

    public function error500(string $error): string
    {
        return $this->pageLoader->setPage('_500')->render(['error' => $error]);
    }
}
