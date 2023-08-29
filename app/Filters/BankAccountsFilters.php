<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class BankAccountsFilters extends QueryFilters
{
    protected array $allowedFilters = [
        'account_number',
        'holder_name',
        'iban'
    ];

    protected array $columnSearch = ['details'];
}
