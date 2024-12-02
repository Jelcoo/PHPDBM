<?php

namespace App\Controllers;

use App\Enum\SuccessEnum;
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

    public function newTable(string $databaseName): string
    {
        return $this->pageLoader->setPage('database/table/new')->render([
            'databaseName' => $databaseName,
        ]);
    }

    public function createTable(string $databaseName)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $this->databaseDiscoveryRepository->createDatabaseTable($databaseName, $data['name'], $data['columns']);

        return json_encode([
            'type' => SuccessEnum::SUCCESS,
            'message' => json_encode($data),
        ]);
    }
}
