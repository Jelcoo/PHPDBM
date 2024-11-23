<?php

namespace App\Helpers;

class Pagination
{
    public static function paginate(array $data, int $totalRecords, int $perPage = 25, int $currentPage = 1): mixed
    {
        $currentPage = (int) $currentPage;
        $pages = [
            'total' => ceil($totalRecords / $perPage),
            'current' => $currentPage,
            'previous' => [],
            'next' => [],
            'perPage' => $perPage,
        ];

        for ($i = 1; $i <= 2; ++$i) {
            if ($currentPage - $i >= 1) {
                $pages['previous'][] = $currentPage - $i;
            }
            if ($currentPage + $i <= $pages['total']) {
                $pages['next'][] = $currentPage + $i;
            }
        }

        return [
            'data' => $data,
            'pages' => $pages,
        ];
    }
}
