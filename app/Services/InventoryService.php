<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\SameLocationMoveException;
use App\Exceptions\InvalidMoveQuantityException;    
use App\Exceptions\ReferenceAlreadyReversedException;
use App\Exceptions\ReferenceNotFoundException;
use App\Exceptions\ReferencePartiallyReversedException;
use App\Models\InventoryLog;
use App\Repositories\InventoryLogRepository;
use App\Repositories\StockRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        return DB::transaction(function () use ($productId, $locationId, $quantity, $userId) {
            $referenceId = (string) Str::uuid();

            $stock = $this->stockRepository->getStock($productId, $locationId);

            if ($stock) {
                $this->stockRepository->updateQuantity($stock, $quantity);
            } else {
                $this->stockRepository->create($productId, $locationId, $quantity);
            }

            $this->inventoryLogRepository->create($productId, $locationId, $quantity, 'IN', $userId, $referenceId);

            return $referenceId;
        });
    }

    public function move($productId, $fromLocationId, $toLocationId, $quantity)
    {
        if ($fromLocationId === $toLocationId) {
            throw new SameLocationMoveException();
        }

        if($quantity <= 0) {
            throw new InvalidMoveQuantityException();
        }

        return DB::transaction(function () use ($productId, $fromLocationId, $toLocationId, $quantity) {
            $referenceId = (string) Str::uuid();
            $from = $this->stockRepository->getStock($productId, $fromLocationId);

            if (!$from || $from->quantity < $quantity) {
                $available = $from ? (int) $from->quantity : 0;
                throw new InsufficientStockException($available, (int) $quantity);
            }

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
                    'reference_id' => $referenceId,
                ],
                [
                    'product_id' => $productId,
                    'location_id' => $toLocationId,
                    'quantity_change' => $quantity,
                    'type' => 'MOVE',
                    'reference_id' => $referenceId,
                ],
            ]);

            return $referenceId;
        });
    }

    private function createMultipleLogs(array $entries)
    {
        foreach ($entries as $entry) {
            $this->inventoryLogRepository->create(
                $entry['product_id'],
                $entry['location_id'],
                $entry['quantity_change'],
                $entry['type'],
                null,
                $entry['reference_id'] ?? null
            );
        }
    }

  public function inventoryInBulk(array $items, $userId = null)
  {
      return DB::transaction(function () use ($items, $userId) {
          $referenceId = (string) Str::uuid();
          foreach ($items as $item) {
              $productId = $item['product_id'];
              $locationId = $item['location_id'];
              $quantity = $item['quantity'];

              if ($quantity <= 0) {
                  throw new \App\Exceptions\InvalidImportException(
                      (int) $productId,
                      (int) $locationId,
                      (int) $quantity
                  );
              }

              $stock = $this->stockRepository->getStock($productId, $locationId);

              if ($stock) {
                  $this->stockRepository->updateQuantity($stock, $quantity);
              } else {
                  $this->stockRepository->create($productId, $locationId, $quantity);
              }

              $this->inventoryLogRepository->create($productId, $locationId, $quantity, 'IN', $userId, $referenceId);
          }

          return $referenceId;
      });
  }

    public function undoByReference(string $referenceId, $userId = null)
    {
        DB::transaction(function () use ($referenceId, $userId) {
            $logs = InventoryLog::where('reference_id', $referenceId)
                ->lockForUpdate()
                ->get();

            if ($logs->isEmpty()) {
                throw new ReferenceNotFoundException($referenceId);
            }

            if ($logs->every(fn ($log) => $log->reversed_at !== null)) {
                throw new ReferenceAlreadyReversedException($referenceId);
            }

            if ($logs->contains(fn ($log) => $log->reversed_at !== null)) {
                throw new ReferencePartiallyReversedException($referenceId);
            }

            $reversalReferenceId = (string) Str::uuid();

            foreach ($logs as $log) {
                $delta = -$log->quantity_change;
                $stock = $this->stockRepository->getStock($log->product_id, $log->location_id);

                if ($delta < 0) {
                    $requested = (int) abs($delta);
                    $available = $stock ? (int) $stock->quantity : 0;

                    if (!$stock || $available < $requested) {
                        throw new InsufficientStockException($available, $requested);
                    }

                    $this->stockRepository->updateQuantity($stock, $delta);

                    if ($stock->quantity == 0) {
                        $stock->delete();
                    }
                } else {
                    if ($stock) {
                        $this->stockRepository->updateQuantity($stock, $delta);
                    } else {
                        $this->stockRepository->create($log->product_id, $log->location_id, $delta);
                    }
                }
            }

            foreach ($logs as $log) {
                $type = $log->type;
                if ($type === 'IN') {
                    $type = 'OUT';
                } elseif ($type === 'OUT') {
                    $type = 'IN';
                }

                $this->inventoryLogRepository->create(
                    $log->product_id,
                    $log->location_id,
                    -$log->quantity_change,
                    $type,
                    $userId,
                    $reversalReferenceId
                );
            }

            InventoryLog::whereIn('id', $logs->pluck('id'))
                ->update(['reversed_at' => now()]);
        });
    }


}
