<?php

namespace App\Controllers;

use App\Helpers\Pagination;
use App\Repositories\DatabaseTableRepository;

class TableController extends Controller
{
    private DatabaseTableRepository $databaseTableRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseTableRepository = new DatabaseTableRepository();
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
        ]);
    }
}
