<?php

namespace App\Controllers;

use App\Repositories\DatabaseDiscoveryRepository;
use App\Repositories\DatabaseTableRepository;

class DatabaseController extends Controller
{
    private DatabaseDiscoveryRepository $databaseDiscoveryRepository;
    private DatabaseTableRepository $tableRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
        $this->tableRepository = new DatabaseTableRepository();
    }

    public function show(string $databaseName): string
    {
        $databaseTables = $this->databaseDiscoveryRepository->getAllTablesFromDatabase($databaseName);

        $formattedTables = [];
        foreach ($databaseTables as $table) {
            $formattedTables[] = [
                'name' => $table,
                'size' => $this->databaseDiscoveryRepository->getTableSize($databaseName, $table),
                'rowCount' => $this->tableRepository->countRowsForTable($databaseName, $table),
            ];
        }

        return $this->pageLoader->setPage('database/view')->render([
            'databaseName' => $databaseName,
            'databaseTables' => $formattedTables,
        ]);
    }
}
