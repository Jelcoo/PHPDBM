<?php

namespace App\Controllers;

use App\Application\Request;
use App\Application\Response;
use App\Database\Database;

class LoginController extends Controller
{
    public function index(): string
    {
        return $this->pageLoader->setPage('login')->render();
    }

    public function login(): string|null
    {
        $ipAddress = Request::getPostField('ip_address');
        $port = Request::getPostField('port');
        $username = Request::getPostField('username');
        $password = Request::getPostField('password');

        var_dump($ipAddress, $port, $username, $password);

        if (empty($ipAddress) || empty($port) || empty($username) || empty($password)) {
            return $this->pageLoader->setPage('login')->render(['error' => 'All fields are required']);
        }

        $db = new Database();
        $db->prepare($ipAddress, $port, $username, $password);
        try {
            $db->getConnection();
        } catch (\Exception $e) {
            Response::redirect('/login');
        }

        return null;
    }
}
