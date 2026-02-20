<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class SurveyFormFilter extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = [
        'name',
        'status',
    ];
}
