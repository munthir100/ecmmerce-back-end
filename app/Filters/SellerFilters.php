<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class SellerFilters extends QueryFilters
{
    protected array $relationSearch = [
        'user' => ['name','email']
    ];
}
