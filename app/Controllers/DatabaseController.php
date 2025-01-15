<?php

namespace App\Controllers;

use App\Enum\SuccessEnum;
use App\Repositories\DatabaseDiscoveryRepository;
use App\Repositories\DatabaseExportRepository;
use App\Repositories\DatabaseTableRepository;

class DatabaseController extends Controller
{
    private DatabaseDiscoveryRepository $databaseDiscoveryRepository;
    private DatabaseTableRepository $tableRepository;
    private DatabaseExportRepository $databaseExportRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
        $this->tableRepository = new DatabaseTableRepository();
        $this->databaseExportRepository = new DatabaseExportRepository();
    }

    public function show(string $databaseName): string
    {
        $this->databaseDiscoveryRepository->useDatabase($databaseName);
        $databaseTables = $this->databaseDiscoveryRepository->getAllTablesFromDatabase();

        $formattedTables = [];
        foreach ($databaseTables as $table) {
            $this->databaseDiscoveryRepository->useDatabase($databaseName)->useTable($table);
            $this->tableRepository->useDatabase($databaseName)->useTable($table);

            $formattedTables[] = [
                'name' => $table,
                'size' => $this->databaseDiscoveryRepository->getTableSize(),
                'rowCount' => $this->tableRepository->countRowsForTable(),
            ];
        }

        return $this->pageLoader->setPage('database/viewDatabase')->render([
            'databaseName' => $databaseName,
            'databaseTables' => $formattedTables,
        ]);
    }

    public function newDatabase(): string
    {
        return $this->pageLoader->setPage('database/newDatabase')->render();
    }

    public function createDatabase(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name'])) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => 'Database name is required',
            ]);
        }

        try {
            $this->databaseDiscoveryRepository->createDatabase($data['name']);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
        }

        return json_encode([
            'type' => SuccessEnum::REDIRECT,
            'message' => 'Database created successfully',
            'redirect' => '/database/' . $data['name'],
        ]);
    }

    public function export(string $databaseName): string
    {
        $this->databaseExportRepository->prepare($_SESSION['ip_address'], $_SESSION['port'], $_SESSION['username'], $_SESSION['password'], $databaseName);

        $exportPath = $this->databaseExportRepository->exportDatabase();
        $fileName = basename($exportPath);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        readfile($exportPath);
        exit;
    }

    public function newTable(string $databaseName): string
    {
        return $this->pageLoader->setPage('database/table/newTable')->render([
            'databaseName' => $databaseName,
        ]);
    }

    public function createTable(string $databaseName)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name'])) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => 'Table name is required',
            ]);
        }

        try {
            $this->databaseDiscoveryRepository
                ->useDatabase($databaseName)
                ->createDatabaseTable($data['name'], $data['columns']);

            return json_encode([
                'type' => SuccessEnum::REDIRECT,
                'message' => 'Table created successfully',
                'redirect' => '/database/' . $databaseName . '/' . $data['name'],
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function editTable(string $databaseName, string $tableName): string
    {
        $tableColumns = $this->tableRepository
            ->useDatabase($databaseName)
            ->useTable($tableName)
            ->getTableColumns();

        return $this->pageLoader->setPage('database/table/editTable')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'tableColumns' => $tableColumns,
        ]);
    }

    public function updateTable(string $databaseName, string $tableName): string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['difference'])) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => 'No changes have been made',
            ]);
        }

        try {
            $this->databaseDiscoveryRepository
                ->useDatabase($databaseName)
                ->useTable($tableName)
                ->updateDatabaseTable($data['difference']);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
        }

        return json_encode([
            'type' => SuccessEnum::REDIRECT,
            'message' => 'Table updated successfully',
            'redirect' => '/database/' . $databaseName . '/' . $tableName . '/edit',
        ]);
    }

    public function delete(string $databaseName): string
    {
        try {
            $this->databaseDiscoveryRepository->deleteDatabase($databaseName);
    
            return json_encode([
                'type' => SuccessEnum::REDIRECT,
                'message' => 'Database deleted successfully',
                'redirect' => '/',
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
            
        }
    }

    public function deleteTable(string $databaseName, string $tableName): string
    {
        try {
            $this->databaseDiscoveryRepository->deleteDatabaseTable($databaseName, $tableName);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
        }

        return json_encode([
            'type' => SuccessEnum::REDIRECT,
            'message' => 'Table deleted successfully',
            'redirect' => '/database/' . $databaseName,
        ]);
    }
}
