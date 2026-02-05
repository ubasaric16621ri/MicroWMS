<?php

namespace App\Http\Controllers;

use App\Repositories\InventoryLogRepository;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoveController extends Controller
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
            'product_id'        => 'required|exists:products,id',
            'from_location_id'  => 'required|exists:locations,id',
            'to_location_id'    => 'required|exists:locations,id',
            'quantity'          => 'required|integer|min:1',
        ]);

        if ($data['from_location_id'] == $data['to_location_id']) {
            return response()->json([
                'message' => 'Cannot move from the same location. Source and destination must be different.',
                'errors' => [
                    'from_location_id' => ['Cannot move from the same location.']
                ]
            ], 422);
        }

        DB::transaction(function () use ($data) {

            $from = $this->stockRepository->getStockWithLock($data['product_id'], $data['from_location_id']);

            if (!$from || $from->quantity < $data['quantity']) {
                abort(400, 'Insufficient stock. Available: ' . ($from?->quantity ?? 0) . ', Requested: ' . $data['quantity']);
            }

            $this->stockRepository->updateQuantity($from, -$data['quantity']);

            $to = $this->stockRepository->getStockWithLock($data['product_id'], $data['to_location_id']);

            if ($to) {
                $this->stockRepository->updateQuantity($to, $data['quantity']);
            } else {
                $this->stockRepository->create($data['product_id'], $data['to_location_id'], $data['quantity']);
            }

            $this->inventoryLogRepository->createMultiple([
                [
                    'product_id' => $data['product_id'],
                    'location_id' => $data['from_location_id'],
                    'quantity_change' => -$data['quantity'],
                    'type' => 'MOVE',
                ],
                [
                    'product_id' => $data['product_id'],
                    'location_id' => $data['to_location_id'],
                    'quantity_change' => $data['quantity'],
                    'type' => 'MOVE',
                ],
            ]);
        });

        return response('', 204);
    }
}
