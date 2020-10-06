<?php

namespace App\Models\Traits;

trait EloquentTrait
{
    public function scopeApiPaginate($query, $pageSize = null)
    {
        $pageSize = $pageSize ?: config('api.pagination.per_page');

        $paginate = $query->paginate($pageSize)->toArray();

        $results = [
            'data' => $paginate['data'],
            'last_page' => $paginate['last_page'],
            'total' => $paginate['total'],
            'current_page' => $paginate['current_page'],
            'from' => $paginate['from'],
            'to' => $paginate['to'],
            'per_page' => $paginate['per_page']
        ];

        return $results;
    }
}
