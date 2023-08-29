<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class ProductFilters extends QueryFilters
{
    protected array $allowedFilters = ['name','sku'];

    protected array $columnSearch = ['name','description','short_description'];
}
