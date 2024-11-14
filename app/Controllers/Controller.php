<?php

namespace App\Controllers;

use App\Application\PageLoader;

class Controller {
    protected PageLoader $pageLoader;

    public function __construct()
    {
        $this->pageLoader = new PageLoader();
    }
}
