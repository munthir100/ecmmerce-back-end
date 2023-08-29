<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class OrderFilters extends QueryFilters
{
    protected array $allowedFilters = ['id','status'];

    protected array $columnSearch = [];
}
