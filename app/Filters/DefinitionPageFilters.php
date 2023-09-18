<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class DefinitionPageFilters extends QueryFilters
{
    protected array $allowedFilters = ['title'];

    protected array $columnSearch = [];
}
