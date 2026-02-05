<?php

namespace App\Repositories;

use App\Models\InventoryLog;

class InventoryLogRepository
{

    public function create($productId, $locationId, $quantityChange, $type, $userId = null)
    {
        return InventoryLog::create([
            'product_id'      => $productId,
            'location_id'     => $locationId,
            'user_id'         => $userId,
            'quantity_change' => $quantityChange,
            'type'            => $type,
        ]);
    }


    public function getByLocation($locationId)
    {
        return InventoryLog::where('location_id', $locationId)
            ->orderByDesc('id')
            ->get();
    }
}