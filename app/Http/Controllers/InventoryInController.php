<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryInController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'location_id' => 'required|exists:locations,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        try {
            $this->inventoryService->inventoryIn($data['product_id'], $data['location_id'], $data['quantity']);

            return response('', 204);

        } catch (\Throwable $e) {
            return response('', 500);
        }
    }   
}
