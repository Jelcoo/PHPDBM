<?php

namespace App\Exceptions;

class InvalidColumnException extends \Exception
{
    public function __construct(string $column)
    {
        parent::__construct(sprintf('The provided column (%s) name does not fulfill the MySQL requirements.', $column));
    }
}
