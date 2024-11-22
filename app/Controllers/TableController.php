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
        $tableColumns = $this->databaseTableRepository->getTableColumns($databaseName, $tableName);
        $tableRows = $this->databaseTableRepository->getAllRowsForTable($databaseName, $tableName);

        $page = $_GET['page'] ?? 1;
        $tableRows = Pagination::paginate($tableRows, 10, $page);

        return $this->pageLoader->setPage('database/table/view')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'tableColumns' => $tableColumns,
            'tableRows' => $tableRows
        ]);
    }
}
