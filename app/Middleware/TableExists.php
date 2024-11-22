<?php

namespace App\Middleware;

use App\Repositories\DatabaseDiscoveryRepository;

class TableExists implements Middleware
{
    private DatabaseDiscoveryRepository $databaseDiscoveryRepository;

    public function __construct()
    {
        $this->databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
    }

    public function verify(array $params = []): bool
    {
        $databaseName = $params[0] ?? null;
        $tableName = $params[1] ?? null;

        return in_array($tableName, $this->databaseDiscoveryRepository->getAllTablesFromDatabase($databaseName));
    }
}
