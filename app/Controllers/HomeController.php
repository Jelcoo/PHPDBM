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

        $formattedDatabases = [];
        foreach ($databases as $database) {
            $formattedDatabases[] = [
                'name' => $database,
                'size' => $this->databaseDiscoveryRepository->useDatabase($database)->getDatabaseSize(),
                'tableCount' => $this->databaseDiscoveryRepository->useDatabase($database)->countDatabaseTables(),
            ];
        }

        return $this->pageLoader->setPage('home')->render([
            'user' => $username,
            'databases' => $formattedDatabases,
        ]);
    }
}
