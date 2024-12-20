<?php

namespace App\Exceptions;

class InvalidTableException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The provided table name does not fulfill the MySQL requirements.');
    }
}
