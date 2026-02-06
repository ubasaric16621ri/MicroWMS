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
            $referenceId = $this->inventoryService->inventoryIn(
                $data['product_id'],
                $data['location_id'],
                $data['quantity']
            );

            return response()->json([
                'message' => 'Inventory received successfully.',
                'reference_id' => $referenceId,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }   
}
