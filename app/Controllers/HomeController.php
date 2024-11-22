<?php

namespace App\Controllers;

use App\Repositories\DatabaseDiscoveryRepository;

class HomeController extends Controller
{
    private DatabaseDiscoveryRepository $databaseDiscoveryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
    }

    public function index(): string
    {
        $username = $_SESSION['username'];
        $databases = $this->databaseDiscoveryRepository->getAllDatabases();

        return $this->pageLoader->setPage('home')->render([
            'user' => $username,
            'databases' => $databases,
        ]);
    }
}
