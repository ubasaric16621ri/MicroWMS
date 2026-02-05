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

    public function createMultiple(array $entries)
    {
        return array_map(function ($entry) {
            return $this->create(
                $entry['product_id'],
                $entry['location_id'],
                $entry['quantity_change'],
                $entry['type'],
                $entry['user_id'] ?? null
            );
        }, $entries);
    }
