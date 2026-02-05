<?php

namespace App\Http\Controllers;

use App\Repositories\InventoryLogRepository;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryInController extends Controller
{
    protected $stockRepository;
    protected $inventoryLogRepository;

    public function __construct(StockRepository $stockRepository, InventoryLogRepository $inventoryLogRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->inventoryLogRepository = $inventoryLogRepository;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'location_id' => 'required|exists:locations,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($data) {

                DB::statement('SET innodb_lock_wait_timeout = 1');

                $stock = $this->stockRepository->getStockWithLock($data['product_id'], $data['location_id']);

                if ($stock) {
                    $this->stockRepository->updateQuantity($stock, $data['quantity']);
                } else {
                    $this->stockRepository->create($data['product_id'], $data['location_id'], $data['quantity']);
                }

                $this->inventoryLogRepository->create($data['product_id'], $data['location_id'], $data['quantity'], 'IN');
            });

            return response('', 204);

        } catch (\Throwable $e) {
            return response('', 500);
        }
    }   
}
