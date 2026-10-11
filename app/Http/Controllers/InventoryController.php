<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // SHOW INVENTORY LIST
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

        // FILTER BY STOCK STATUS
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out_of_stock') {
                $query->where('quantity', 0);
            } elseif ($request->stock_status === 'low_stock') {
                $query->whereColumn('quantity', '<=', 'reorder_level')
                      ->where('quantity', '>', 0);
            } elseif ($request->stock_status === 'in_stock') {
                $query->whereColumn('quantity', '>', 'reorder_level');
            }
        }

        // PAGINATED RESULTS
        $products = $query->orderBy('item_code')->paginate(10)->withQueryString();

        // SUMMARY COUNTS (BASED ON ALL PRODUCTS, NOT FILTERED)
        $totalItems      = Product::count();
        $inStockCount    = Product::whereColumn('quantity', '>', 'reorder_level')->count();
        $lowStockCount   = Product::whereColumn('quantity', '<=', 'reorder_level')->where('quantity', '>', 0)->count();
        $outOfStockCount = Product::where('quantity', 0)->count();

        return view('inventory.index', compact(
            'products',
            'totalItems',
            'inStockCount',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    // SHOW ADD ITEM FORM
    public function create()
    {
        return view('inventory.create');
    }

    // STORE NEW ITEM
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string'],
            'category'      => ['required', 'in:machines,tools,accessories'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_cost'     => ['required', 'numeric', 'min:0'],
            'price'         => ['required', 'numeric', 'min:0'],
            'description'   => ['nullable', 'string'],
        ]);

        // CHECK FOR DUPLICATE PRODUCT NAME (CASE-INSENSITIVE)
        $duplicate = Product::whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])->first();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'A product with this name already exists (' . $duplicate->item_code . '). Please use a different name.']);
        }

        // AUTO-GENERATE ITEM CODE BASED ON CATEGORY
        $validated['item_code'] = $this->generateItemCode($validated['category']);

        $product = Product::create($validated);

        // LOG INITIAL STOCK IF QUANTITY IS MORE THAN ZERO
        if ($product->quantity > 0) {
            StockLog::create([
                'product_id'      => $product->id,
                'quantity_before' => 0,
                'quantity_added'  => $product->quantity,
                'quantity_after'  => $product->quantity,
                'notes'           => 'Initial stock on item creation',
            ]);

            AuditLog::record(
                action: 'stock_added',
                description: 'Added ' . $product->quantity . ' units of ' . $product->name . ' (' . $product->item_code . ') as initial stock.',
                subjectType: 'Product',
                subjectId: $product->id,
            );
        }

        return redirect()->route('inventory.index')->with('success', 'Item added successfully.');
    }

    // BUILD THE NEXT ITEM CODE FOR A GIVEN CATEGORY
    private function generateItemCode($category)
    {
        $prefixes = [
            'machines'    => 'MCH',
            'tools'       => 'TOL',
            'accessories' => 'ACC',
        ];

        $prefix = $prefixes[$category] ?? 'GEN';

        $lastProduct = Product::where('item_code', 'like', $prefix . '-%')
            ->orderByDesc('item_code')
            ->first();

        if ($lastProduct) {
            $lastNumber = (int) str_replace($prefix . '-', '', $lastProduct->item_code);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // SHOW EDIT ITEM FORM
    public function edit(Product $product)
    {
        return view('inventory.edit', compact('product'));
    }

    // UPDATE ITEM
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_cost'     => ['required', 'numeric', 'min:0'],
            'price'         => ['required', 'numeric', 'min:0'],
            'description'   => ['nullable', 'string'],
        ]);

        $oldQuantity = $product->quantity;
        $newQuantity = (int) $validated['quantity'];

        // LOG STOCK IN IF QUANTITY WAS INCREASED
        if ($newQuantity > $oldQuantity) {
            StockLog::create([
                'product_id'      => $product->id,
                'quantity_before' => $oldQuantity,
                'quantity_added'  => $newQuantity - $oldQuantity,
                'quantity_after'  => $newQuantity,
                'notes'           => 'Restocked via inventory edit',
            ]);

            AuditLog::record(
                action: 'stock_added',
                description: 'Restocked ' . ($newQuantity - $oldQuantity) . ' units of ' . $product->name . ' (' . $product->item_code . ').',
                subjectType: 'Product',
                subjectId: $product->id,
            );
        }

        // ITEM CODE AND CATEGORY STAY FIXED, NOT INCLUDED IN UPDATE
        $product->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Item updated successfully.');
    }

    // SHOW ADD STOCK FORM
    public function addStockForm(Product $product)
    {
        return view('inventory.add-stock', compact('product'));
    }

    // PROCESS ADD STOCK
    public function addStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity_to_add' => ['required', 'integer', 'min:1'],
        ]);

        $oldQuantity = $product->quantity;
        $addedQty    = (int) $validated['quantity_to_add'];
        $newQuantity = $oldQuantity + $addedQty;

        // UPDATE PRODUCT QUANTITY
        $product->update(['quantity' => $newQuantity]);

        // LOG STOCK ADDITION
        StockLog::create([
            'product_id'      => $product->id,
            'quantity_before' => $oldQuantity,
            'quantity_added'  => $addedQty,
            'quantity_after'  => $newQuantity,
            'notes'           => 'Stock added via Add Stock',
        ]);

        AuditLog::record(
            action: 'stock_added',
            description: 'Added ' . $addedQty . ' units of ' . $product->name . ' (' . $product->item_code . '). Stock: ' . $oldQuantity . ' → ' . $newQuantity . '.',
            subjectType: 'Product',
            subjectId: $product->id,
        );

        return redirect()->route('inventory.index')->with('success', 'Stock updated successfully.');
    }
}