<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request): void {
                $q->where('product_code', 'like', '%' . $request->search . '%')
                    ->orWhere('product_name', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->paginate(15);

        return view('inventory.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('inventory.products.create');
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        if (empty($data['product_code'])) {
            $lastProduct = Product::withTrashed()->orderBy('id', 'desc')->first();
            $lastId = $lastProduct ? $lastProduct->id : 0;
            $data['product_code'] = 'PRD' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        }
        
        Product::create($data);

        return redirect()->route('erp.inventory.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $product->load(['finishedGoodsTransactions' => function ($query) {
            $query->latest()->limit(10);
        }]);
        
        return view('inventory.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('inventory.products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('erp.inventory.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('erp.inventory.products.index')->with('success', 'Product deleted successfully.');
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update([
            'status' => $product->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('erp.inventory.products.index')->with('success', 'Product status updated successfully.');
    }
}
