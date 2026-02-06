<?php

namespace App\Services;

use App\Exceptions\ExportEmptyException;
use App\Models\InventoryLog;
use App\Models\Stock;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryExportService
{
    public function streamLogsCsv(): StreamedResponse
    {
        if (!InventoryLog::query()->exists()) {
            throw new ExportEmptyException('inventory_logs');
        }

        $filename = 'inventory_logs.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'id',
                'product_id',
                'location_id',
                'user_id',
                'reference_id',
                'reversed_at',
                'quantity_change',
                'type',
                'created_at'
            ]);

            InventoryLog::orderBy('id')->chunk(100, function ($logs) use ($out) {
                foreach ($logs as $log) {
                    fputcsv($out, [
                        $log->id,
                        $log->product_id,
                        $log->location_id,
                        $log->user_id,
                        $log->reference_id,
                        $log->reversed_at,
                        $log->quantity_change,
                        $log->type,
                        $log->created_at,
                    ]);
                }
            });

            fclose($out);
        }, 200, $headers);
    }

    public function streamStockCsv(): StreamedResponse
    {
        if (!Stock::query()->exists()) {
            throw new ExportEmptyException('stocks');
        }

        $filename = 'current_stock.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'id',
                'product_id',
                'location_id',
                'quantity',
                'created_at',
                'updated_at'
            ]);

            Stock::orderBy('id')->chunk(500, function ($stocks) use ($out) {
                foreach ($stocks as $stock) {
                    fputcsv($out, [
                        $stock->id,
                        $stock->product_id,
                        $stock->location_id,
                        $stock->quantity,
                        $stock->created_at,
                        $stock->updated_at,
                    ]);
                }
            });

            fclose($out);
        }, 200, $headers);
    }
}
