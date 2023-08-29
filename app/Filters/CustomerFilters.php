<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class CustomerFilters extends QueryFilters
{
    protected array $relationSearch = [
        'user' => [
            'name', 'email', 'phone','description'
        ]
    ];
}
