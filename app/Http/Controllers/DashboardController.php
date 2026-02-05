<?php

namespace App\Http\Controllers;

use App\Repositories\LocationRepository;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $stockRepository;
    protected $locationRepository;

    public function __construct(StockRepository $stockRepository, LocationRepository $locationRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->locationRepository = $locationRepository;
    }

    public function index(Request $request)
    {
        $perPage = min($request->query('per_page', 15), 100);
        
        $totalsByProduct = $this->stockRepository->getProductsWithTotalStock($perPage);

        $emptyLocations = $this->locationRepository->getEmptyLocations();

        return response()->json([
            'totals_by_product' => $totalsByProduct,
            'empty_locations'   => $emptyLocations,
        ]);
    }
}
