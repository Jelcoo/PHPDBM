<?php

namespace App\Middleware;

use App\Repositories\DatabaseDiscoveryRepository;

class DatabaseExists implements Middleware
{
    public function verify(array $params = []): bool
    {
        $databaseDiscoveryRepository = new DatabaseDiscoveryRepository();

        $databaseName = $params[0] ?? null;

        return in_array($databaseName, $databaseDiscoveryRepository->getAllDatabases());
    }
}
