<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class CaptainFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = ['name'];
}
