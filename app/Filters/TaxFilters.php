<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class TaxFilters extends QueryFilters
{
    protected array $allowedFilters = ['number','name','precentage'];

    protected array $columnSearch = ['name'];
}