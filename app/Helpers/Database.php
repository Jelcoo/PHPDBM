<?php

namespace App\Helpers;

class Database
{
    public static function getPrimaryKey(array $columns)
    {
        foreach ($columns as $column) {
            if ($column['Key'] === 'PRI') {
                return $column['Field'];
            }
        }
        return null;
    }
}
