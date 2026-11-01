<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::withCount('sales')
            ->withSum('sales', 'total')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('phone_number', 'like', '%' . $search . '%');
            })
            ->orderByDesc('sales_sum_total')
            ->paginate(10)
            ->withQueryString();

        // TOP SPENDERS
        $topSpenderIds = Customer::withSum('sales', 'total')
            ->orderByDesc('sales_sum_total')
            ->limit(5)
            ->pluck('id');

        // STATS
        $totalCustomers   = Customer::count();
        $totalRevenue     = Customer::withSum('sales', 'total')->get()->sum('sales_sum_total');
        $averageSpend     = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;

        return view('customer.index', compact(
            'customers',
            'search',
            'topSpenderIds',
            'totalCustomers',
            'totalRevenue',
            'averageSpend'
        ));
    }

    // SHOW
    public function show(Customer $customer)
    {
        $totalSpent  = $customer->sales()->sum('total');
        $totalOrders = $customer->sales()->count();

        $sales = $customer->sales()
            ->with(['items', 'user'])
            ->latest()
            ->paginate(5);

        return view('customer.show', compact(
            'customer',
            'totalSpent',
            'totalOrders',
            'sales'
        ));
    }
}