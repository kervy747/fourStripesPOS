<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
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
        // DEFAULT TO CURRENT MONTH (1ST TO TODAY) IF NO FILTER IS APPLIED
        $dateFrom = $request->filled('date_from') ? $request->input('date_from') : now()->startOfMonth()->toDateString();
        $dateTo   = $request->filled('date_to')   ? $request->input('date_to')   : now()->toDateString();

        $sales = Sale::with(['customer', 'user', 'items'])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // STAT CARDS
        $totalRevenue = Sale::whereBetween('created_at', [
            $dateFrom . ' 00:00:00', $dateTo . ' 23:59:59',
        ])->sum('total');

        $totalTransactions = Sale::whereBetween('created_at', [
            $dateFrom . ' 00:00:00', $dateTo . ' 23:59:59',
        ])->count();

        $averageOrderValue = $totalTransactions > 0
            ? $totalRevenue / $totalTransactions
            : 0;

        $totalItemsSold = SaleItem::whereBetween('created_at', [
            $dateFrom . ' 00:00:00', $dateTo . ' 23:59:59',
        ])->sum('quantity');

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
        // DEFAULT TO CURRENT MONTH (1ST TO TODAY) IF NO FILTER IS APPLIED
        $dateFrom = $request->filled('date_from') ? $request->input('date_from') : now()->startOfMonth()->toDateString();
        $dateTo   = $request->filled('date_to')   ? $request->input('date_to')   : now()->toDateString();

        $sales = Sale::with(['customer', 'user', 'items'])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->latest()
            ->get();
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

        AuditLog::record(
            action: 'print_report',
            description: 'Printed sales report from ' . $dateFrom . ' to ' . $dateTo . '.',
        );

        return $pdf->stream('sales-report.pdf');
    }

    // INVENTORY REPORT INDEX
    public function inventoryReport(Request $request)
    {
        // DEFAULT TO TODAY IF NO FILTER IS APPLIED
        $today    = now()->toDateString();
        $dateFrom = $request->filled('date_from') ? $request->input('date_from') : $today;
        $dateTo   = $request->filled('date_to')   ? $request->input('date_to')   : $today;

        // ALL PRODUCTS REGARDLESS OF ACTIVITY
        $products = Product::orderBy('name')->get();

        // STOCK IN FROM STOCK LOGS
        $stockIn = StockLog::whereBetween('created_at', [
            $dateFrom . ' 00:00:00',
            $dateTo   . ' 23:59:59',
        ])->get()->groupBy('product_id');

        // STOCK OUT FROM SALE ITEMS
        $stockOut = SaleItem::whereBetween('created_at', [
            $dateFrom . ' 00:00:00',
            $dateTo   . ' 23:59:59',
        ])->get()->groupBy('product_id');

        // BEGINNING BALANCE PER PRODUCT
        $beginningBalances = [];

        foreach ($products as $product) {
            $firstLog = StockLog::where('product_id', $product->id)
                ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->orderBy('created_at')
                ->first();

            if ($firstLog) {
                $beginningBalances[$product->id] = $firstLog->quantity_before;
            } else {
                $beginningBalances[$product->id] = $product->quantity;
            }
        }

        return view('reports.inventory', compact(
            'products',
            'stockIn',
            'stockOut',
            'beginningBalances',
            'dateFrom',
            'dateTo',
        ));
    }

    // INVENTORY PRINT PDF
    public function inventoryPdf(Request $request)
    {
        // DEFAULT TO TODAY IF NO FILTER IS APPLIED
        $today    = now()->toDateString();
        $dateFrom = $request->filled('date_from') ? $request->input('date_from') : $today;
        $dateTo   = $request->filled('date_to')   ? $request->input('date_to')   : $today;

        // ALL PRODUCTS REGARDLESS OF ACTIVITY
        $products = Product::orderBy('name')->get();

        $stockIn = StockLog::whereBetween('created_at', [
            $dateFrom . ' 00:00:00',
            $dateTo   . ' 23:59:59',
        ])->get()->groupBy('product_id');

        $stockOut = SaleItem::whereBetween('created_at', [
            $dateFrom . ' 00:00:00',
            $dateTo   . ' 23:59:59',
        ])->get()->groupBy('product_id');

        // BEGINNING BALANCE PER PRODUCT
        $beginningBalances = [];

        foreach ($products as $product) {
            $firstLog = StockLog::where('product_id', $product->id)
                ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->orderBy('created_at')
                ->first();

            if ($firstLog) {
                $beginningBalances[$product->id] = $firstLog->quantity_before;
            } else {
                $beginningBalances[$product->id] = $product->quantity;
            }
        }

        $pdf = Pdf::loadView('reports.inventory_pdf', compact(
            'products',
            'stockIn',
            'stockOut',
            'beginningBalances',
            'dateFrom',
            'dateTo',
        ))->setPaper('a4', 'portrait');

        AuditLog::record(
            action: 'print_report',
            description: 'Printed inventory report from ' . $dateFrom . ' to ' . $dateTo . '.',
        );

        return $pdf->stream('inventory-report.pdf');
    }
}