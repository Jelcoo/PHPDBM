<?php

namespace App\Controllers;

use App\Enum\SuccessEnum;
use App\Repositories\DatabaseDiscoveryRepository;

class HomeController extends Controller
{
    private DatabaseDiscoveryRepository $databaseDiscoveryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseDiscoveryRepository = new DatabaseDiscoveryRepository();
    }

    public function index(): string
    {
        $username = $_SESSION['username'];
        $ipAddress = $_SESSION['ip_address'];
        $port = $_SESSION['port'];
        $databases = $this->databaseDiscoveryRepository->getAllDatabases();

        $formattedDatabases = [];
        foreach ($databases as $database) {
            $formattedDatabases[] = [
                'name' => $database,
                'size' => $this->databaseDiscoveryRepository->useDatabase($database)->getDatabaseSize(),
                'tableCount' => $this->databaseDiscoveryRepository->useDatabase($database)->countDatabaseTables(),
            ];
        }

        return $this->pageLoader->setPage('home')->render([
            'username' => $username,
            'ipAddress' => $ipAddress,
            'port' => $port,
            'databases' => $formattedDatabases,
        ]);
    }

    public function run(): string
    {
        $username = $_SESSION['username'];
        $ipAddress = $_SESSION['ip_address'];
        $port = $_SESSION['port'];

        return $this->pageLoader->setPage('runSql')->render([
            'username' => $username,
            'ipAddress' => $ipAddress,
            'port' => $port,
        ]);
    }

    public function runSql(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['sql'])) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => 'SQL is required',
            ]);
        }

        try {
            $this->databaseDiscoveryRepository->runRawSql($data['sql']);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
        }

        return json_encode([
            'type' => SuccessEnum::SUCCESS,
            'message' => 'SQL executed successfully',
        ]);
    }
}
