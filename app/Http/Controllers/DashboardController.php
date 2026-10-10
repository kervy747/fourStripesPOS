<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // DASHBOARD INDEX
    public function index() {

        $totalItems      = Product::count();
        $inStockCount    = Product::whereColumn('quantity', '>', 'reorder_level')->count();
        $lowStockCount   = Product::whereColumn('quantity', '<=', 'reorder_level')->where('quantity', '>', 0)->count();
        $outOfStockCount = Product::where('quantity', 0)->count();

        return view('dashboard.index', compact(
            'totalItems',
            'inStockCount',
            'lowStockCount',
            'outOfStockCount'
        ));
    }
}
