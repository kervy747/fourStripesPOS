<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    // DASHBOARD INDEX
    public function index() {

        $totalItems      = Product::count();
        $inStockCount    = Product::whereColumn('quantity', '>', 'reorder_level')->count();
        $lowStockCount   = Product::whereColumn('quantity', '<=', 'reorder_level')->where('quantity', '>', 0)->count();
        $outOfStockCount = Product::where('quantity', 0)->count();

        // OUT OF STOCK PRODUCT LIST
        $outOfStockProducts = Product::where('quantity', 0)
            ->orderBy('name')
            ->limit(5)
            ->get();

        // LOW STOCK PRODUCT LIST
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'reorder_level')
            ->where('quantity', '>', 0)
            ->orderBy('quantity')
            ->limit(5)
            ->get();

        // TOP PRODUCTS THIS MONTH
        $topProducts = SaleItem::selectRaw('product_id, item_name, SUM(quantity) as total_units, SUM(subtotal) as total_revenue')
            ->whereHas('sale', function ($q) {
                $q->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
            })
            ->groupBy('product_id', 'item_name')
            ->orderByDesc('total_units')
            ->get();

        return view('dashboard.index', compact(
            'totalItems',
            'inStockCount',
            'lowStockCount',
            'outOfStockCount',
            'outOfStockProducts',
            'lowStockProducts',
            'topProducts'
        ));
    }
}