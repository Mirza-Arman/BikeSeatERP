<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductionFormula;
use App\Models\ProductionFormulaItem;
use App\Models\RawMaterial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionFormulaController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProductionFormula::query()->with('product');

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%');
            });
        }

        $formulas = $query->latest()->paginate(15);

        return view('production.formula.index', compact('formulas'));
    }

    public function create(): View
    {
        $products = Product::where('status', 'active')->orderBy('product_name')->get();
        $rawMaterials = RawMaterial::where('status', 'active')->orderBy('name')->get();

        return view('production.formula.create', compact('products', 'rawMaterials'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'version' => 'required|string',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.quantity_required' => 'required|numeric|min:0',
            'items.*.unit' => 'nullable|string',
        ]);

        $formula = ProductionFormula::create([
            'product_id' => $request->product_id,
            'version' => $request->version,
            'description' => $request->description,
        ]);

        foreach ($request->items as $item) {
            $formula->items()->create($item);
        }

        return redirect()->route('erp.production.formula.index')->with('success', 'Production formula created successfully.');
    }

    public function show(ProductionFormula $formula): View
    {
        $formula->load(['product', 'items.rawMaterial']);

        return view('production.formula.show', compact('formula'));
    }

    public function edit(ProductionFormula $formula): View
    {
        $formula->load('items');
        $products = Product::where('status', 'active')->orderBy('product_name')->get();
        $rawMaterials = RawMaterial::where('status', 'active')->orderBy('name')->get();

        return view('production.formula.edit', compact('formula', 'products', 'rawMaterials'));
    }

    public function update(Request $request, ProductionFormula $formula): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'version' => 'required|string',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.quantity_required' => 'required|numeric|min:0',
            'items.*.unit' => 'nullable|string',
        ]);

        $formula->update([
            'product_id' => $request->product_id,
            'version' => $request->version,
            'description' => $request->description,
        ]);

        $formula->items()->delete();

        foreach ($request->items as $item) {
            $formula->items()->create($item);
        }

        return redirect()->route('erp.production.formula.index')->with('success', 'Production formula updated successfully.');
    }

    public function destroy(ProductionFormula $formula): RedirectResponse
    {
        $formula->delete();

        return redirect()->route('erp.production.formula.index')->with('success', 'Production formula deleted successfully.');
    }
}
