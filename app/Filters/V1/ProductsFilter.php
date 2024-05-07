<?php

namespace App\Filters\v1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class ProductsFilter extends ApiFilter {

    protected $safeParms = [
        'name'   => ['eq'],
        'slug'   => ['eq'],
        'active' => ['eq'],
        'price'  => ['eq', 'gt', 'lt'],
    ];

    protected $columnMap = [];

    protected $operatorMap = [
        'eq'  => '=',
        'lt'  => '<',
        'lte' => '<=',
        'gt'  => '>',
        'gte' => '>=',
    ];
}
