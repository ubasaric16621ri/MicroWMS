<?php

namespace App\Services;

use App\Policies\InventoryPolicy;
use App\Repositories\InventoryLogRepository;
use App\Repositories\StockRepository;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    protected $stockRepository;
    protected $inventoryLogRepository;

    public function __construct(StockRepository $stockRepository, InventoryLogRepository $inventoryLogRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->inventoryLogRepository = $inventoryLogRepository;
    }

    public function inventoryIn($productId, $locationId, $quantity, $userId = null)
    {
        DB::transaction(function () use ($productId, $locationId, $quantity, $userId) {

            $stock = $this->stockRepository->getStock($productId, $locationId);

            if ($stock) {
                $this->stockRepository->updateQuantity($stock, $quantity);
            } else {
                $this->stockRepository->create($productId, $locationId, $quantity);
            }

            $this->inventoryLogRepository->create($productId, $locationId, $quantity, 'IN', $userId);
        });
    }

    public function move($productId, $fromLocationId, $toLocationId, $quantity)
    {
        DB::transaction(function () use ($productId, $fromLocationId, $toLocationId, $quantity) {
            $from = $this->stockRepository->getStock($productId, $fromLocationId);

            InventoryPolicy::ensureSufficientStock($from, $quantity);

            $this->stockRepository->updateQuantity($from, -$quantity);

            if ($from->quantity == 0) {
                $from->delete();
            }

            $to = $this->stockRepository->getStock($productId, $toLocationId);

            if ($to) {
                $this->stockRepository->updateQuantity($to, $quantity);
            } else {
                $this->stockRepository->create($productId, $toLocationId, $quantity);
            }

            $this->createMultipleLogs([
                [
                    'product_id' => $productId,
                    'location_id' => $fromLocationId,
                    'quantity_change' => -$quantity,
                    'type' => 'MOVE',
                ],
                [
                    'product_id' => $productId,
                    'location_id' => $toLocationId,
                    'quantity_change' => $quantity,
                    'type' => 'MOVE',
                ],
            ]);
        });
    }

    private function createMultipleLogs(array $entries)
    {
        foreach ($entries as $entry) {
            $this->inventoryLogRepository->create(
                $entry['product_id'],
                $entry['location_id'],
                $entry['quantity_change'],
                $entry['type']
            );
        }
    }
}
