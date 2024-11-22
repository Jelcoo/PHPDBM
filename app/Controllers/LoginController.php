<?php

namespace App\Controllers;

use App\Application\Request;
use App\Application\Session;
use App\Application\Response;
use App\Repositories\DatabaseRepository;

class LoginController extends Controller
{
    private DatabaseRepository $databaseRepository;

    public function __construct()
    {
        parent::__construct();
        $this->databaseRepository = new DatabaseRepository();
    }

    public function index(): string
    {
        return $this->pageLoader->setPage('login')->render();
    }

    public function login(): ?string
    {
        $ipAddress = Request::getPostField('ip_address');
        $port = Request::getPostField('port');
        $username = Request::getPostField('username');
        $password = Request::getPostField('password');

        if (empty($ipAddress) || empty($port) || empty($username) || empty($password)) {
            return $this->rerender([
                'error' => 'All fields are required',
                'fields' => $_POST,
            ]);
        }

        $this->databaseRepository->prepare($ipAddress, $port, $username, $password);
        try {
            $this->databaseRepository->getConnection();
            $_SESSION['ip_address'] = $ipAddress;
            $_SESSION['port'] = $port;
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            Response::redirect('/');
        } catch (\Exception $e) {
            return $this->rerender([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        return null;
    }

    public function logout(): void
    {
        Session::destroy();
        Response::redirect('/login');
    }

    private function rerender(array $parameters = []): string
    {
        return $this->pageLoader->setPage('login')->render($parameters);
    }
}
