<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\RawMaterialRequest;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Services\RawMaterialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    private RawMaterialService $rawMaterialService;

    public function __construct(RawMaterialService $rawMaterialService)
    {
        $this->rawMaterialService = $rawMaterialService;
    }

    public function index(Request $request): View
    {
        $query = RawMaterial::with(['category', 'supplier']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request): void {
                $q->where('material_code', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhereJsonContains('attributes->model', $request->search)
                    ->orWhereJsonContains('attributes->brand', $request->search)
                    ->orWhereJsonContains('attributes->quality', $request->search);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('current_stock', '<=', 'minimum_stock');
            } elseif ($request->stock_status === 'out') {
                $query->where('current_stock', 0);
            } elseif ($request->stock_status === 'normal') {
                $query->whereColumn('current_stock', '>', 'minimum_stock');
            }
        }

        $materials = $query->latest()->paginate(15);
        $categories = MaterialCategory::orderBy('name')->get();
        $suppliers = Supplier::orderBy('company_name')->get();
        $stockSummary = $this->rawMaterialService->getStockSummary();

        return view('raw-materials.index', compact('materials', 'categories', 'suppliers', 'stockSummary'));
    }

    public function create(): View
    {
        $categories = MaterialCategory::with('attributes')->orderBy('name')->get();
        $suppliers = Supplier::orderBy('company_name')->get();

        return view('raw-materials.create', compact('categories', 'suppliers'));
    }

    public function store(RawMaterialRequest $request): RedirectResponse
    {
        if ($this->rawMaterialService->checkDuplicateMaterial($request->validated())) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Material with these attributes already exists for this supplier.');
        }

        $material = $this->rawMaterialService->createRawMaterial(
            $request->validated(),
            auth()->id()
        );

        return redirect()->route('erp.raw-materials.show', $material)
            ->with('success', 'Raw material created successfully.');
    }

    public function show(RawMaterial $rawMaterial): View
    {
        $detail = $this->rawMaterialService->getMaterialDetail($rawMaterial);

        return view('raw-materials.show', compact('rawMaterial', 'detail'));
    }

    public function edit(RawMaterial $rawMaterial): View
    {
        $categories = MaterialCategory::with('attributes')->orderBy('name')->get();
        $suppliers = Supplier::orderBy('company_name')->get();

        return view('raw-materials.edit', compact('rawMaterial', 'categories', 'suppliers'));
    }

    public function update(RawMaterialRequest $request, RawMaterial $rawMaterial): RedirectResponse
    {
        $rawMaterial = $this->rawMaterialService->updateRawMaterial(
            $rawMaterial,
            $request->validated()
        );

        return redirect()->route('erp.raw-materials.show', $rawMaterial)
            ->with('success', 'Raw material updated successfully.');
    }

    public function destroy(RawMaterial $rawMaterial): RedirectResponse
    {
        $rawMaterial->delete();

        return redirect()->route('erp.raw-materials.index')
            ->with('success', 'Raw material deleted successfully.');
    }

    public function toggleStatus(RawMaterial $rawMaterial): RedirectResponse
    {
        $rawMaterial->update([
            'status' => $rawMaterial->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('erp.raw-materials.index')
            ->with('success', 'Raw material status updated successfully.');
    }
}
