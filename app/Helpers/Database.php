<?php

namespace App\Helpers;

use PhpMyAdmin\SqlParser\Parser;

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

    public static function splitSql(string $sqlString): array
    {
        $statements = [];

        $parser = new Parser($sqlString);
        foreach ($parser->statements as $statement) {
            $statements[] = $statement->build();
        }

        return $statements;
    }
}
