<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InventoryService;

class BulkReceiveController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.location_id' => 'required|exists:locations,id',
            'items.*.quantity' => 'required|integer',
        ]);

        try {
            $referenceId = $this->inventoryService->inventoryInBulk($data['items'], $request->user()?->id);
            return response()->json([
                'message' => 'Bulk inventory received successfully.',
                'reference_id' => $referenceId,
            ], 200);
        } catch (\App\Exceptions\InvalidImportException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
