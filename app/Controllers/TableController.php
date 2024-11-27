<?php

namespace App\Controllers;

use App\Helpers\Database as DatabaseHelpers;
use App\Helpers\Pagination;
use App\Repositories\DatabaseTableRepository;
use App\Repositories\DatabaseUpdateRepository;
use App\Enum\SuccessEnum;

class TableController extends Controller
{
    private DatabaseTableRepository $databaseTableRepository;
    private DatabaseUpdateRepository $databaseUpdateRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseTableRepository = new DatabaseTableRepository();
        $this->databaseUpdateRepository = new DatabaseUpdateRepository();
    }

    public function show(string $databaseName, string $tableName): string
    {
        $page = $_GET['page'] ?? 1;
        $perPage = $_GET['size'] ?? 25;
        $search = $_GET['search'] ?? null;

        $tableColumns = $this->databaseTableRepository->getTableColumns($databaseName, $tableName);
        if ($search) {
            $tableRows = $this->databaseTableRepository->getPagedSearchedRowsForTable($databaseName, $tableName, $page, $perPage, $search);
            $totalRecords = $this->databaseTableRepository->countSearchedRowsForTable($databaseName, $tableName, $search);
        } else {
            $tableRows = $this->databaseTableRepository->getPagedRowsForTable($databaseName, $tableName, $page, $perPage);
            $totalRecords = $this->databaseTableRepository->countRowsForTable($databaseName, $tableName);
        }

        $tableRows = Pagination::paginate($tableRows, $totalRecords, $perPage, $page);

        return $this->pageLoader->setPage('database/table/view')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'tableColumns' => $tableColumns,
            'tableRows' => $tableRows,
            'primaryKey' => DatabaseHelpers::getPrimaryKey($tableColumns),
        ]);
    }

    public function newRow(string $databaseName, string $tableName): string
    {
        $tableColumns = $this->databaseTableRepository->getTableColumns($databaseName, $tableName);

        return $this->pageLoader->setPage('database/table/row/new')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'tableColumns' => $tableColumns,
        ]);
    }

    public function createRow(string $databaseName, string $tableName)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $createStatement = [];
        foreach ($data as $rowField) {
            if ($rowField['value'] == '') {
                continue;
            }
            $createStatement[$rowField['field']] = $rowField['null'] ? null : $rowField['value'];
        }

        try {
            $insertedId = $this->databaseUpdateRepository->createRow($databaseName, $tableName, $createStatement);

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
        $tableColumns = $this->databaseTableRepository->getTableColumns($databaseName, $tableName);
        $primaryKey = DatabaseHelpers::getPrimaryKey($tableColumns);
        $tableRow = $this->databaseTableRepository->getRowByKey($databaseName, $tableName, $primaryKey, $key);

        return $this->pageLoader->setPage('database/table/row/edit')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'primaryKey' => $key,
            'tableColumns' => $tableColumns,
            'tableRow' => $tableRow,
        ]);
    }

    public function updateRow(string $databaseName, string $tableName, string $key)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $tableColumns = $this->databaseTableRepository->getTableColumns($databaseName, $tableName);
        $primaryKey = DatabaseHelpers::getPrimaryKey($tableColumns);
        $tableRow = $this->databaseTableRepository->getRowByKey($databaseName, $tableName, $primaryKey, $key);

        $updateStatement = [];
        foreach ($data as $rowField) {
            if ($rowField['value'] != $tableRow[$rowField['field']]) {
                $updateStatement[$rowField['field']] = $rowField['null'] ? null : $rowField['value'];
            }
        }

        if (empty($updateStatement)) {
            return json_encode([
                'type' => SuccessEnum::WARNING,
                'message' => 'No rows have changed',
            ]);
        }

        try {
            $this->databaseUpdateRepository->updateRow($databaseName, $tableName, $primaryKey, $key, $updateStatement);
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
}
