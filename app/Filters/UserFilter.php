<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class UserFilter extends QueryFilters
{
    protected array $allowedFilters = [

    ];

    protected array $columnSearch = [  
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'username',
    ];
}
