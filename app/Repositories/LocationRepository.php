<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LocationRepository
{

    public function getEmptyLocations()
    {
        return DB::table('locations')
            ->leftJoin('stocks', 'locations.id', '=', 'stocks.location_id')
            ->select(
                'locations.id',
                'locations.code',
                'locations.type',
                DB::raw('COALESCE(SUM(stocks.quantity), 0) as total_quantity')
            )
            ->groupBy('locations.id', 'locations.code', 'locations.type')
            ->havingRaw('COALESCE(SUM(stocks.quantity), 0) = 0')
            ->orderBy('locations.id')
            ->get();
    }

  
    public function getTotalCount()
    {
        return DB::table('locations')->count();
    }
}
