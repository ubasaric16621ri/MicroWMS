<?php

namespace App\Http\Controllers;

use App\Exceptions\ExportEmptyException;
use App\Services\InventoryExportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryExportController extends Controller
{
    protected $exportService;

    public function __construct(InventoryExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function logs(Request $request): Response
    {
        try {
            return $this->exportService->streamLogsCsv();
        } catch (ExportEmptyException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function stock(Request $request): Response
    {
        try {
            return $this->exportService->streamStockCsv();
        } catch (ExportEmptyException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
