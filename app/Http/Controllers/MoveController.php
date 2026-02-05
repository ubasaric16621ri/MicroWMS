<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\SameLocationMoveException;
use App\Policies\InventoryPolicy;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class MoveController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'        => 'required|exists:products,id',
            'from_location_id'  => 'required|exists:locations,id',
            'to_location_id'    => 'required|exists:locations,id',
            'quantity'          => 'required|integer|min:1',
        ]);

        try {
            InventoryPolicy::ensureDifferentLocations($data['from_location_id'], $data['to_location_id']);
            $this->inventoryService->move($data['product_id'], $data['from_location_id'], $data['to_location_id'], $data['quantity']);

            return response('', 204);
        } catch (SameLocationMoveException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => [
                    'from_location_id' => ['Cannot move from the same location.']
                ]
            ], 422);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
