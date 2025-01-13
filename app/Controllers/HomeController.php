<?php

namespace App\Controllers;

use App\Enum\SuccessEnum;
use App\Helpers\Database as DatabaseHelper;
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
        $databases = $this->databaseDiscoveryRepository->getAllDatabases();

        return $this->pageLoader->setPage('runSql')->render([
            'username' => $username,
            'ipAddress' => $ipAddress,
            'port' => $port,
            'databases' => $databases,
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
        
        $databaseName = empty($data['database']) ? null : $data['database'];

        try {
            $statements = DatabaseHelper::splitSql($data['sql']);
        } catch (\Exception $e) {
            return json_encode([
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ]);
        }

        try {
            $messages = [];
            foreach ($statements as $statement) {
                $stmtResult = $this->databaseDiscoveryRepository->runSql($databaseName, $statement);
                $messages[] = [
                    'type' => SuccessEnum::SUCCESS,
                    'data' => $stmtResult,
                    'original' => $statement,
                ];
            }
        } catch (\Exception $e) {
            $messages[] = [
                'type' => SuccessEnum::FAILURE,
                'message' => $e->getMessage(),
            ];
        }

        return json_encode([
            'type' => SuccessEnum::SUCCESS,
            'message' => 'SQL executed successfully',
            'messages' => $messages,
        ]);
    }

    public function users(): string
    {
        $username = $_SESSION['username'];
        $ipAddress = $_SESSION['ip_address'];
        $port = $_SESSION['port'];
        $users = $this->databaseDiscoveryRepository->getAllUsers();

        return $this->pageLoader->setPage('users')->render([
            'username' => $username,
            'ipAddress' => $ipAddress,
            'port' => $port,
            'users' => $users,
        ]);
    }

    public function connections(): string
    {
        $username = $_SESSION['username'];
        $ipAddress = $_SESSION['ip_address'];
        $port = $_SESSION['port'];
        $connections = $this->databaseDiscoveryRepository->getAllConnections();

        return $this->pageLoader->setPage('connections')->render([
            'username' => $username,
            'ipAddress' => $ipAddress,
            'port' => $port,
            'connections' => $connections,
        ]);
    }

    public function bookmarks(): string
    {
        return $this->pageLoader->setPage('bookmarks')->render();
    }
}
