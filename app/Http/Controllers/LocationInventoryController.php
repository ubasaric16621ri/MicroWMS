<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Repositories\InventoryLogRepository;
use App\Repositories\StockRepository;

class LocationInventoryController extends Controller
{
    protected $stockRepository;
    protected $inventoryLogRepository;

    public function __construct(StockRepository $stockRepository, InventoryLogRepository $inventoryLogRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->inventoryLogRepository = $inventoryLogRepository;
    }

    public function show(Location $location)
    {
        $inventory = $this->stockRepository->getByLocation($location->id);

        $logs = $this->inventoryLogRepository->getByLocation($location->id);

        return response()->json([
            'location'  => [
                'id'   => $location->id,
                'code' => $location->code,
                'type' => $location->type,
            ],
            'inventory' => $inventory,
            'logs'      => $logs,
        ]);
    }
}
