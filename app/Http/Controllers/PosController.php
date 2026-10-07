<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    // SHOW POS SCREEN
    public function index(Request $request)
    {
        $query = Product::query();

        // SEARCH BY NAME OR ITEM CODE
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        // FILTER BY CATEGORY
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // PAGINATED PRODUCT LIST
        $products = $query->orderBy('item_code')->paginate(8)->withQueryString();

        // GET CART AND CUSTOMER INFO FROM SESSION
        $cart = session('cart', []);
        $notes = session('cart_notes', '');
        $customerName = session('cart_customer_name', '');
        $customerPhone = session('cart_customer_phone', '');
        $customerAddress = session('cart_customer_address', '');
        $selectedCustomerId = session('cart_customer_id');
        $customerMatches = session('customer_matches', []);
        $cashReceived = session('cart_cash_received', '');

        // CALCULATE TOTAL
        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // CALCULATE CHANGE
        $change = is_numeric($cashReceived) ? max($cashReceived - $total, 0) : 0;

        return view('pos.index', compact(
            'products',
            'cart',
            'notes',
            'customerName',
            'customerPhone',
            'customerAddress',
            'selectedCustomerId',
            'customerMatches',
            'cashReceived',
            'change',
            'total'
        ));
    }

    // CUSTOMER UPDATE
    public function customerUpdate(Request $request)
    {
        // FIND EXISTING CUSTOMER BY NAME
        if ($request->has('find_customer')) {
            $name = $request->input('customer_name');

            $matches = Customer::where('name', 'like', "%{$name}%")->get();

            session(['customer_matches' => $matches->toArray()]);

            return redirect()->route('pos.index');
        }

        // SELECT A MATCHED CUSTOMER
        if ($request->has('select_customer')) {
            $customer = Customer::find($request->input('select_customer'));

            if ($customer) {
                session(['cart_customer_id'      => $customer->id]);
                session(['cart_customer_name'    => $customer->name]);
                session(['cart_customer_phone'   => $customer->phone_number]);
                session(['cart_customer_address' => $customer->address]);
            }

            session()->forget('customer_matches');

            return redirect()->route('pos.index');
        }

        // CLEAR SELECTED CUSTOMER
        if ($request->has('clear_customer')) {
            session()->forget(['cart_customer_id', 'customer_matches']);

            return redirect()->route('pos.index');
        }
    }
    
    // ADD PRODUCT TO CART
    public function add(Product $product)
    {
        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            // DO NOT EXCEED AVAILABLE STOCK
            if ($cart[$product->id]['quantity'] < $product->quantity) {
                $cart[$product->id]['quantity']++;
            }
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'item_code'  => $product->item_code,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => 1,
                'available'  => $product->quantity,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back();
    }

    // UPDATE CART - INCREASE / DECREASE / REMOVE / CUSTOMER SEARCH / CHECKOUT
    public function updateCart(Request $request)
    {
        $cart = session('cart', []);

        // ALWAYS SAVE NOTES, CUSTOMER INFO, AND CASH RECEIVED
        session(['cart_notes'             => $request->input('notes', '')]);
        session(['cart_customer_name'     => $request->input('customer_name', '')]);
        session(['cart_customer_phone'    => $request->input('customer_phone', '')]);
        session(['cart_customer_address'  => $request->input('customer_address', '')]);
        session(['cart_cash_received'     => $request->input('cash_received', '')]);

        // CALCULATE CHANGE
        if ($request->has('calculate_change')) {
            return redirect()->route('pos.index');
        }

        // INCREASE QUANTITY
        if ($request->has('increase')) {
            $id = (int) $request->input('increase');

            if (isset($cart[$id]) && $cart[$id]['quantity'] < $cart[$id]['available']) {
                $cart[$id]['quantity']++;
            }
        }

        // DECREASE QUANTITY
        if ($request->has('decrease')) {
            $id = (int) $request->input('decrease');

            if (isset($cart[$id])) {
                $cart[$id]['quantity']--;

                if ($cart[$id]['quantity'] <= 0) {
                    unset($cart[$id]);
                }
            }
        }

        // UPDATE TYPED QUANTITY
        if (
            $request->has('quantity') &&
            !$request->has('increase') &&
            !$request->has('decrease')
        ) {
            foreach ($request->input('quantity') as $id => $quantity) {
                $id = (int) $id;
                $quantity = (int) $quantity;

                if (isset($cart[$id])) {
                    $quantity = max(1, min($quantity, $cart[$id]['available']));
                    $cart[$id]['quantity'] = $quantity;
                }
            }
        }          

        // REMOVE ITEM
        if ($request->has('remove')) {
            $id = (int) $request->input('remove');
            unset($cart[$id]);
        }

        session(['cart' => $cart]);

        // CHECKOUT
        if ($request->has('checkout')) {
            return $this->checkout($request, $cart);
        }

        return redirect()->route('pos.index');
    }

    // FINALIZE THE SALE
    private function checkout(Request $request, array $cart)
    {
        if (empty($cart)) {
            return redirect()->route('pos.index')->with('error', 'Cart is empty.');
        }

        // CALCULATE TOTAL
        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // VALIDATE CUSTOMER AND CASH INFO
        $validated = $request->validate([
            'customer_name'    => ['required', 'string'],
            'customer_phone'   => ['nullable', 'string'],
            'customer_address' => ['required', 'string'],
            'cash_received'    => ['required', 'numeric', 'min:0'],
        ]);

        // CASH MUST COVER THE TOTAL
        if ($validated['cash_received'] < $total) {
            return redirect()->route('pos.index')->with('error', 'Cash received is less than the total.');
        }

        $change = max($validated['cash_received'] - $total, 0);

        // GET OR CREATE CUSTOMER
        $customerId = session('cart_customer_id');

        if ($customerId) {
            $customer = Customer::find($customerId);
        } else {
            $customer = Customer::create([
                'name'         => $validated['customer_name'],
                'phone_number' => $validated['customer_phone'] ?? null,
                'address'      => $validated['customer_address'],
            ]);
        }

        // CREATE THE SALE
        $sale = Sale::create([
            'customer_id'    => $customer->id,
            'user_id'        => Auth::id(),
            'cash_received'  => $validated['cash_received'],
            'change'         => $change,
            'notes'          => $request->input('notes'),
            'total'          => $total,
        ]);

        // CREATE SALE ITEMS AND DEDUCT INVENTORY
        foreach ($cart as $item) {
            SaleItem::create([
                'sale_id'   => $sale->id,
                'product_id'=> $item['product_id'],
                'item_code' => $item['item_code'],
                'item_name' => $item['name'],
                'quantity'  => $item['quantity'],
                'price'     => $item['price'],
                'subtotal'  => $item['price'] * $item['quantity'],
            ]);

            $product = Product::find($item['product_id']);

            if ($product) {
                $product->decrement('quantity', $item['quantity']);
            }
        }

        // CLEAR CART AND CUSTOMER SESSION DATA
        session()->forget([
            'cart',
            'cart_notes',
            'cart_customer_name',
            'cart_customer_phone',
            'cart_customer_address',
            'cart_customer_id',
            'customer_matches',
            'cart_cash_received',
        ]);

        return redirect()->route('pos.index')
            ->with('success', 'Sale completed successfully.')
            ->with('receipt_url', route('receipts.show', $sale->id));
    }
}