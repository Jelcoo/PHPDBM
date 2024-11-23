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

        $tableColumns = $this->databaseTableRepository->getTableColumns($databaseName, $tableName);
        $tableRows = $this->databaseTableRepository->getPagedRowsForTable($databaseName, $tableName, $page, $perPage);
        $totalRecords = $this->databaseTableRepository->countRowsForTable($databaseName, $tableName);

        $tableRows = Pagination::paginate($tableRows, $totalRecords, $perPage, $page);

        return $this->pageLoader->setPage('database/table/view')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'tableColumns' => $tableColumns,
            'tableRows' => $tableRows,
        ]);
    }
}
