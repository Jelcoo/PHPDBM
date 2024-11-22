<?php

namespace App\Middleware;

use App\Application\Session;
use App\Application\Response;
use App\Repositories\DatabaseDiscoveryRepository;

class DatabaseExists implements Middleware
{
    private DatabaseDiscoveryRepository $databaseDiscoveryRepository;

    public function __construct()
    {
        $this->databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
    }

    public function verify(array $params = []): bool
    {
        $databaseName = $params[0] ?? null;

        return in_array($databaseName, $this->databaseDiscoveryRepository->getAllDatabases());
    }
}
