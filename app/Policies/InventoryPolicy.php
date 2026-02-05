<?php

namespace App\Policies;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\SameLocationMoveException;
use App\Models\Stock;

class InventoryPolicy
{
    public static function ensureDifferentLocations(int $fromLocationId, int $toLocationId): void
    {
        if ($fromLocationId === $toLocationId) {
            throw new SameLocationMoveException();
        }
    }

    public static function ensureSufficientStock(?Stock $stock, int $quantity): void
    {
        $available = $stock ? $stock->quantity : 0;

        if ($available < $quantity) {
            throw new InsufficientStockException($available, $quantity);
        }
    }
}
