<?php

namespace App\Middleware;

use App\Repositories\DatabaseDiscoveryRepository;

class TableExists implements Middleware
{
    public function verify(array $params = []): bool
    {
        $databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
        
        $databaseName = $params[0] ?? null;
        $tableName = $params[1] ?? null;

        return in_array($tableName, $databaseDiscoveryRepository->useDatabase($databaseName)->getAllTablesFromDatabase());
    }
}
