<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        $products = $query->orderBy('item_code')->paginate(10)->withQueryString();

        // GET CART FROM SESSION
        $cart = session('cart', []);
        $notes = session('cart_notes', '');
        $customerName = session('cart_customer_name', '');

        // CALCULATE TOTAL
        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('pos.index', compact('products', 'cart', 'notes', 'customerName', 'total'));
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
                'item_code' => $product->item_code,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'available' => $product->quantity,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back();
    }

    // UPDATE CART - INCREASE / DECREASE / REMOVE / CHECKOUT
    public function updateCart(Request $request)
    {
        $cart = session('cart', []);

        // ALWAYS SAVE NOTES AND CUSTOMER NAME, SINCE THEY COME WITH EVERY SUBMIT
        session(['cart_notes' => $request->input('notes', '')]);
        session(['cart_customer_name' => $request->input('customer_name', '')]);

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

        // REMOVE ITEM
        if ($request->has('remove')) {
            $id = (int) $request->input('remove');
            unset($cart[$id]);
        }

        session(['cart' => $cart]);

        // CHECKOUT (PENDING OR COMPLETED)
        if ($request->has('checkout')) {
            if (empty($cart)) {
                return redirect()->route('pos.index')->with('error', 'Cart is empty.');
            }

            // FULL CHECKOUT (CUSTOMER LOOKUP, SALE, SALE ITEMS, INVENTORY DEDUCTION)
            // COMING IN THE NEXT STEP — SALES MODULE NOT BUILT YET
            return redirect()->route('pos.index')->with('info', 'Checkout coming soon — the Sales module is next.');
        }

        return redirect()->route('pos.index');
    }
}