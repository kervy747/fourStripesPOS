<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    // SHOW SALES TRACKING PAGE
    public function index(Request $request)
    {
        // BASE QUERY
        $query = Sale::with(['customer', 'user', 'items.product']);

        // STATUS TAB FILTER (all / pending / completed)
        $status = $request->query('status', 'all');
        if ($status === 'pending' || $status === 'completed') {
            $query->where('status', $status);
        }

        // SEARCH BY OR NUMBER (E.G. "0009") OR CUSTOMER NAME
        if ($request->filled('search')) {
            $search = $request->query('search');
            $digitsOnly = preg_replace('/\D/', '', $search);

            $query->where(function ($sub) use ($search, $digitsOnly) {
                if ($digitsOnly !== '') {
                    $sub->orWhereRaw("LPAD(id, 4, '0') LIKE ?", ["%{$digitsOnly}%"]);
                }

                $sub->orWhereHas('customer', function ($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%");
                });
            });
        }

        // NEWEST FIRST, PAGINATED
        $sales = $query->latest()->paginate(15)->withQueryString();

        // SELECTED TRANSACTION FOR THE SIDE PANEL
        $selectedSale = null;
        if ($request->filled('selected')) {
            $selectedSale = Sale::with(['customer', 'user', 'items.product'])
                ->find($request->query('selected'));
        }

        return view('sales.index', [
            'sales' => $sales,
            'selectedSale' => $selectedSale,
            'status' => $status,
        ]);
    }
}