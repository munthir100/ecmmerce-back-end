<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class CouponFilters extends QueryFilters
{
    protected array $allowedFilters = ['promocode'];

    protected array $columnSearch = [];
}
