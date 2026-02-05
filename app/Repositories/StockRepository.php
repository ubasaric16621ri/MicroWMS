<?php

namespace App\Repositories;

use App\Models\Stock;

class StockRepository
{
    public function getStock($productId, $locationId)
    {
        return Stock::where('product_id', $productId)
            ->where('location_id', $locationId)
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


    public function getByLocation($locationId)
    {
        return Stock::where('location_id', $locationId)
            ->orderBy('product_id')
            ->get();
    }
}