<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidMoveQuantityException;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\SameLocationMoveException;
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
            'quantity'          => 'required|integer',
        ]);

        try {
            $referenceId = $this->inventoryService->move(
                $data['product_id'],
                $data['from_location_id'],
                $data['to_location_id'],
                $data['quantity']
            );

            return response()->json([
                'message' => 'Inventory moved successfully.',
                'reference_id' => $referenceId,
            ], 200);
        } catch (InvalidMoveQuantityException | SameLocationMoveException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function bulkMove(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.from_location_id' => 'required|exists:locations,id',
            'items.*.to_location_id' => 'required|exists:locations,id',
            'items.*.quantity' => 'required|integer',
        ]);

        try {
            $referenceId = $this->inventoryService->bulkMove($data['items']);

            return response()->json([
                'message' => 'Bulk inventory moved successfully.',
                'reference_id' => $referenceId,
            ], 200);
        } catch (InvalidMoveQuantityException | SameLocationMoveException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
