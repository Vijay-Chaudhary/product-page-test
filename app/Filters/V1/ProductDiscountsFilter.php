<?php

namespace App\Filters\v1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class ProductDiscountsFilter extends ApiFilter {

    protected $safeParms = [
        'name'   => ['eq'],
        'product_id'   => ['eq'],
        'type'   => ['eq'],
        'discount' => ['eq'],
    ];

    protected $columnMap = [
        'productId' => 'product_id'
    ];

    protected $operatorMap = [
        'eq'  => '=',
        'lt'  => '<',
        'lte' => '<=',
        'gt'  => '>',
        'gte' => '>=',
    ];
}
