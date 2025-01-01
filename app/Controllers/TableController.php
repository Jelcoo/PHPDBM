<?php

namespace App\Controllers;

use App\Helpers\Database as DatabaseHelpers;
use App\Helpers\Pagination;
use App\Repositories\DatabaseTableRepository;
use App\Repositories\DatabaseUpdateRepository;
use App\Enum\SuccessEnum;
use App\Repositories\DatabaseExportRepository;

class TableController extends Controller
{
    private DatabaseTableRepository $databaseTableRepository;
    private DatabaseUpdateRepository $databaseUpdateRepository;
    private DatabaseExportRepository $databaseExportRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseTableRepository = new DatabaseTableRepository();
        $this->databaseUpdateRepository = new DatabaseUpdateRepository();
        $this->databaseExportRepository = new DatabaseExportRepository();
    }

    public function show(string $databaseName, string $tableName): string
    {
        $this->databaseTableRepository->useDatabase($databaseName)->useTable($tableName);

        $page = $_GET['page'] ?? 1;
        $perPage = $_GET['size'] ?? 25;
        $search = $_GET['search'] ?? null;

        $tableColumns = $this->databaseTableRepository->getTableColumns();
        if ($search) {
            $tableRows = $this->databaseTableRepository->getPagedSearchedRowsForTable($page, $perPage, $search);
            $totalRecords = $this->databaseTableRepository->countSearchedRowsForTable($search);
        } else {
            $tableRows = $this->databaseTableRepository->getPagedRowsForTable($page, $perPage);
            $totalRecords = $this->databaseTableRepository->countRowsForTable();
        }

        $tableRows = Pagination::paginate($tableRows, $totalRecords, $perPage, $page);

        return $this->pageLoader->setPage('database/table/viewTable')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'tableColumns' => $tableColumns,
            'tableRows' => $tableRows,
            'primaryKey' => DatabaseHelpers::getPrimaryKey($tableColumns),
        ]);
    }

    public function newRow(string $databaseName, string $tableName): string
    {
        $this->databaseTableRepository->useDatabase($databaseName)->useTable($tableName);
        $tableColumns = $this->databaseTableRepository->getTableColumns();

        return $this->pageLoader->setPage('database/table/row/newRow')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'tableColumns' => $tableColumns,
        ]);
    }

    public function createRow(string $databaseName, string $tableName)
    {
        $this->databaseTableRepository->useDatabase($databaseName)->useTable($tableName);

        $data = json_decode(file_get_contents('php://input'), true);

        $createStatement = [];
        foreach ($data as $rowField) {
            if ($rowField['value'] == '') {
                continue;
            }
            $createStatement[$rowField['field']] = $rowField['null'] ? null : $rowField['value'];
        }

        try {
            $insertedId = $this->databaseUpdateRepository->createRow($createStatement);

            return json_encode([
                'type' => SuccessEnum::SUCCESS,
                'message' => 'Row created successfully. ID: ' . $insertedId,
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function editRow(string $databaseName, string $tableName, string $key): string
    {
        $this->databaseTableRepository->useDatabase($databaseName)->useTable($tableName);

        $tableColumns = $this->databaseTableRepository->getTableColumns();
        $primaryKey = DatabaseHelpers::getPrimaryKey($tableColumns);
        $tableRow = $this->databaseTableRepository->getRowByKey($primaryKey, $key);

        return $this->pageLoader->setPage('database/table/row/editRow')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'primaryKey' => $key,
            'tableColumns' => $tableColumns,
            'tableRow' => $tableRow,
        ]);
    }

    public function updateRow(string $databaseName, string $tableName, string $key)
    {
        $this->databaseTableRepository->useDatabase($databaseName)->useTable($tableName);
        $this->databaseUpdateRepository->useDatabase($databaseName)->useTable($tableName);

        $data = json_decode(file_get_contents('php://input'), true);

        $tableColumns = $this->databaseTableRepository->getTableColumns();
        $primaryKey = DatabaseHelpers::getPrimaryKey($tableColumns);
        $tableRow = $this->databaseTableRepository->getRowByKey($primaryKey, $key);

        $updateStatement = [];
        foreach ($data as $rowField) {
            if ($rowField['value'] != $tableRow[$rowField['field']]) {
                $updateStatement[$rowField['field']] = $rowField['null'] ? null : $rowField['value'];
            }
        }

        if (empty($updateStatement)) {
            return json_encode([
                'type' => SuccessEnum::WARNING,
                'message' => 'No fields have changed',
            ]);
        }

        try {
            $this->databaseUpdateRepository->updateRow($primaryKey, $key, $updateStatement);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
        }

        return json_encode([
            'type' => SuccessEnum::SUCCESS,
            'message' => 'Row updated successfully',
        ]);
    }

    public function export(string $databaseName, string $tableName): string
    {
        $this->databaseExportRepository->prepare($_SESSION['ip_address'], $_SESSION['port'], $_SESSION['username'], $_SESSION['password'], $databaseName, $tableName);

        $exportPath = $this->databaseExportRepository->exportTable();
        $fileName = basename($exportPath);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        readfile($exportPath);
        exit;
    }
}
