<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class ReportFilter extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = [
            'record_id',
    ];
}
