<?php

namespace App\Controllers;

class ErrorController extends Controller {
    public function error404()
    {
        return $this->pageLoader->setPage('_404')->render();
    }
}
