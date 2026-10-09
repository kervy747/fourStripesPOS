<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // SALES REPORT INDEX
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        $query = Sale::with(['customer', 'user', 'items']);

        // DATE RANGE FILTER
        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [
                $dateFrom . ' 00:00:00',
                $dateTo   . ' 23:59:59',
            ]);
        }

        $sales = $query->latest()->paginate(15)->withQueryString();

        // STAT CARDS
        $totalRevenue = Sale::when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })->sum('total');

        $totalTransactions = Sale::when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })->count();

        $averageOrderValue = $totalTransactions > 0
            ? $totalRevenue / $totalTransactions
            : 0;

        $totalItemsSold = SaleItem::when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })->sum('quantity');

        return view('reports.index', compact(
            'sales',
            'totalRevenue',
            'totalTransactions',
            'averageOrderValue',
            'totalItemsSold',
            'dateFrom',
            'dateTo',
        ));
    }

    // SALES PRINT PDF
    public function printPdf(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        $query = Sale::with(['customer', 'user', 'items']);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [
                $dateFrom . ' 00:00:00',
                $dateTo   . ' 23:59:59',
            ]);
        }

        $sales             = $query->latest()->get();
        $totalRevenue      = $sales->sum('total');
        $totalTransactions = $sales->count();
        $averageOrderValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        $totalItemsSold    = $sales->flatMap->items->sum('quantity');

        $pdf = Pdf::loadView('reports.pdf', compact(
            'sales',
            'totalRevenue',
            'totalTransactions',
            'averageOrderValue',
            'totalItemsSold',
            'dateFrom',
            'dateTo',
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('sales-report.pdf');
    }

    // INVENTORY REPORT INDEX
    public function inventoryReport(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        // ALL PRODUCTS REGARDLESS OF ACTIVITY
        $products = Product::orderBy('name')->get();

        // STOCK IN FROM STOCK LOGS
        $stockIn = StockLog::when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })->get()->groupBy('product_id');

        // STOCK OUT FROM SALE ITEMS
        $stockOut = SaleItem::when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })->get()->groupBy('product_id');

        return view('reports.inventory', compact(
            'products',
            'stockIn',
            'stockOut',
            'dateFrom',
            'dateTo',
        ));
    }

    // INVENTORY PRINT PDF
    public function inventoryPdf(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        // ALL PRODUCTS REGARDLESS OF ACTIVITY
        $products = Product::orderBy('name')->get();

        $stockIn = StockLog::when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })->get()->groupBy('product_id');

        $stockOut = SaleItem::when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })->get()->groupBy('product_id');

        $pdf = Pdf::loadView('reports.inventory_pdf', compact(
            'products',
            'stockIn',
            'stockOut',
            'dateFrom',
            'dateTo',
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('inventory-report.pdf');
    }
}