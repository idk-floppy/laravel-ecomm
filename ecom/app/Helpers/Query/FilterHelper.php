<?php

namespace App\Helpers\Query;

class FilterHelper
{
    public function applyFilters($query, $request, array $filters)
    {
        foreach ($filters as $parameter => $method) {
            if ($request->has($parameter) && $request->input($parameter) != null) {
                $query->$method($request->input($parameter));
            }
        }

        return $query;
    }
}
