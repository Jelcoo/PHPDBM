<?php

namespace App\Controllers;

use App\Repositories\DatabaseDiscoveryRepository;

class DatabaseController extends Controller
{
    private DatabaseDiscoveryRepository $databaseDiscoveryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
    }

    public function show(string $databaseName): string
    {
        $databaseTables = $this->databaseDiscoveryRepository->GetAllTablesFromDatabase($databaseName);

        return $this->pageLoader->setPage('database/view')->render([
            'databaseName' => $databaseName,
            'databaseTables' => $databaseTables
        ]);
    }
}
