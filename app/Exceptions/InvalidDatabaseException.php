<?php

namespace App\Exceptions;

class InvalidDatabaseException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The provided database name does not fulfill the MySQL requirements.');
    }
}
