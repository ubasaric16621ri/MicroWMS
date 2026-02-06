<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\ReferenceAlreadyReversedException;
use App\Exceptions\ReferenceNotFoundException;
use App\Exceptions\ReferencePartiallyReversedException;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryUndoController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reference_id' => 'required|uuid',
        ]);

        try {
            $this->inventoryService->undoByReference($data['reference_id'], $request->user()?->id);

            return response()->json(['message' => 'Inventory operation reversed successfully.'], 200);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (
            ReferenceNotFoundException |
            ReferenceAlreadyReversedException |
            ReferencePartiallyReversedException $e
        ) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
