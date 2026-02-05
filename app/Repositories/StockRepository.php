<?php

namespace App\Repositories;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockRepository
{
    public function getStockWithLock($productId, $locationId)
    {
        return Stock::where('product_id', $productId)
            ->where('location_id', $locationId)
            ->lockForUpdate()
            ->first();
    }

    public function create($productId, $locationId, $quantity)
    {
        return Stock::create([
            'product_id'  => $productId,
            'location_id' => $locationId,
            'quantity'    => $quantity,
        ]);
    }

    public function updateQuantity($stock, $quantityChange)
    {
        $stock->quantity += $quantityChange;
        $stock->save();
        return $stock;
    }


    public function getPaginated($perPage = 15)
    {
        return Stock::with(['product', 'location'])
            ->orderBy('product_id')
            ->paginate($perPage);
    }


    public function getTotalCount()
    {
        return Stock::count();
    }


    public function getTotalQuantity()
    {
        return Stock::sum('quantity') ?? 0;
    }

    public function getByLocation($locationId)
    {
        return Stock::where('location_id', $locationId)
            ->orderBy('product_id')
            ->get();
    }

    public function getProductsWithTotalStock($perPage = 15)
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
}