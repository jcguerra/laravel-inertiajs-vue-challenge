<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationService
{
    /**
     * Get pagination
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public function getPagination(LengthAwarePaginator $paginator): array
    {
        return [
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem()
            ]
        ];
    }
}