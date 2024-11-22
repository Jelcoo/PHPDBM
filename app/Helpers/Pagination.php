<?php

namespace App\Helpers;

class Pagination
{
    public static function paginate($data, $perPage = 10, $currentPage = 1): mixed
    {
        $currentPage = (int) $currentPage;
        $pagedData = array_slice($data, ($currentPage - 1) * $perPage, $perPage);
        $pages = [
            'total' => ceil(count($data) / $perPage),
            'current' => $currentPage,
            'previous' => [],
            'next' => []
        ];

        for ($i = 1; $i <= 2; $i++) {
            if ($currentPage - $i >= 1) {
                $pages['previous'][] = $currentPage - $i;
            }
            if ($currentPage + $i <= $pages['total']) {
                $pages['next'][] = $currentPage + $i;
            }
        }
        
        return [
            'data' => $pagedData,
            'pages' => $pages
        ];
    }
}
