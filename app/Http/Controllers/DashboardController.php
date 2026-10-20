<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    // DASHBOARD INDEX
    public function index(Request $request) {

        $totalItems      = Product::count();
        $inStockCount    = Product::whereColumn('quantity', '>', 'reorder_level')->count();
        $lowStockCount   = Product::whereColumn('quantity', '<=', 'reorder_level')->where('quantity', '>', 0)->count();
        $outOfStockCount = Product::where('quantity', 0)->count();

        // OUT OF STOCK PRODUCT LIST
        $outOfStockProducts = Product::where('quantity', 0)
            ->orderBy('name')
            ->paginate(5, ['*'], 'out_page');

        // LOW STOCK PRODUCT LIST
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'reorder_level')
            ->where('quantity', '>', 0)
            ->orderBy('quantity')
            ->paginate(5, ['*'], 'low_page');

        // FILTER VALUES FROM REQUEST
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear  = $request->input('year', Carbon::now()->year);

        // TOP PRODUCTS BAR CHART DATA
        $topProducts = SaleItem::selectRaw('item_name, SUM(quantity) as total_units, SUM(subtotal) as total_revenue')
            ->whereHas('sale', function ($q) use ($selectedMonth, $selectedYear) {
                $q->whereMonth('created_at', $selectedMonth)
                  ->whereYear('created_at', $selectedYear);
            })
            ->groupBy('item_name')
            ->orderByDesc('total_units')
            ->limit(10)
            ->get();

        // AVAILABLE YEARS FOR FILTER (FROM FIRST SALE UP TO NOW)
        $years = SaleItem::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([Carbon::now()->year]);
        }

        return view('dashboard.index', compact(
            'totalItems',
            'inStockCount',
            'lowStockCount',
            'outOfStockCount',
            'outOfStockProducts',
            'lowStockProducts',
            'topProducts',
            'selectedMonth',
            'selectedYear',
            'years'
        ));
    }
}