<?php

namespace App\Controllers;

use App\Database\Database;
use App\Application\Request;
use App\Application\Session;
use App\Application\Response;

class LoginController extends Controller
{
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

        $db = new Database();
        $db->prepare($ipAddress, $port, $username, $password);
        try {
            $db->getConnection();
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
