<?php

use Illuminate\Pagination\LengthAwarePaginator;

function paginateCollection($collection, $limit = 20, $pageName = 'page', $fragment = null)
{
    $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
    $currentPageItems = $collection->slice(($currentPage - 1) * $limit, $limit);
    
    $paginator = new LengthAwarePaginator(
        $currentPageItems,
        $collection->count(),
        $limit,
        $currentPage,
        [
            'pageName' => 'page',
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'fragment' => $fragment
        ]
    );

    return $paginator;
}
