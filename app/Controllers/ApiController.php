<?php

namespace App\Controllers;

use App\Enum\SuccessEnum;
use App\Helpers\Database as DatabaseHelper;
use App\Repositories\DatabaseDiscoveryRepository;

class ApiController extends Controller
{
    private DatabaseDiscoveryRepository $databaseDiscoveryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
    }

    public function databases(): array
    {
        $databases = $this->databaseDiscoveryRepository->getAllDatabases();

        $formattedDatabases = [];
        foreach ($databases as $database) {
            $formattedDatabases[] = [
                'name' => $database,
                'size' => $this->databaseDiscoveryRepository->useDatabase($database)->getDatabaseSize(),
                'tableCount' => $this->databaseDiscoveryRepository->useDatabase($database)->countDatabaseTables(),
            ];
        }

        return $formattedDatabases;
    }
}
