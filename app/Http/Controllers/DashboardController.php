<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $data = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $data['per_page'] ?? 15;
        
        $totalsByProduct = $this->dashboardService->getTotalsByProduct($perPage);
        $emptyLocations = $this->dashboardService->getEmptyLocations();

        return response()->json([
            'totals_by_product' => $totalsByProduct,
            'empty_locations'   => $emptyLocations->count(),
        ]);
    }
}
