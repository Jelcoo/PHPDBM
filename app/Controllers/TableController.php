<?php

namespace App\Controllers;

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

        return $this->pageLoader->setPage('database/table/view')->render([
            'databaseName' => $databaseName,
            'tableName' => $tableName,
            'tableColumns' => $tableColumns,
            'tableRows' => $tableRows
        ]);
    }
}
