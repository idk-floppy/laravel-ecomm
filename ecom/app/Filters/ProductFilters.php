<?php

namespace App\Filters;

use App\Interfaces\Filterable;

class ProductFilters implements Filterable
{
    public static function getFilters()
    {
        return [
            'name' => 'filterByName',
            'minPrice' => 'filterByMinPrice',
            'maxPrice' => 'filterByMaxPrice',
            'orderBy' => 'orderBy',
        ];
    }
}
