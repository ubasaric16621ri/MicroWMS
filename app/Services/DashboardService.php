<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getTotalsByProduct($perPage = 15)
    {
        return DB::table('products')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->select(
                'products.id',
                'products.sku',
                'products.name',
                DB::raw('COALESCE(SUM(stocks.quantity), 0) as total_quantity')
            )
            ->groupBy('products.id', 'products.sku', 'products.name')
            ->orderBy('products.id')
            ->paginate($perPage);
    }

    public function getEmptyLocations()
    {
        $emptyCount = \App\Models\Location::whereNotIn('id', function ($q) {
            $q->select('location_id')
                ->from('stocks')
                ->where('quantity', '>', 0);
        })
            ->count();
        return $emptyCount;
    }
}
