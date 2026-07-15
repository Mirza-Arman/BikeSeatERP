<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\RawMaterials\RawMaterialRequest;
use App\Models\MaterialCategory;
use App\Models\RawMaterial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    public function index(Request $request): View
    {
        $query = RawMaterial::query()->with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request): void {
                $q->where('material_code', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $materials = $query->latest()->paginate(15);

        return view('raw-materials.index', compact('materials'));
    }

    public function create(): View
    {
        $categories = MaterialCategory::orderBy('name')->get();

        return view('raw-materials.create', compact('categories'));
    }

    public function store(RawMaterialRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Auto-generate material code if not provided
        if (empty($data['material_code'])) {
            $lastMaterial = RawMaterial::withTrashed()->orderBy('id', 'desc')->first();
            $lastId = $lastMaterial ? $lastMaterial->id : 0;
            $data['material_code'] = 'MAT' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        }
        
        RawMaterial::create($data);

        return redirect()->route('erp.raw-materials.index')->with('success', 'Raw material created successfully.');
    }

    public function show(RawMaterial $rawMaterial): View
    {
        $rawMaterial->load(['category', 'stockTransactions' => function ($query) {
            $query->latest()->limit(10);
        }]);
        
        return view('raw-materials.show', compact('rawMaterial'));
    }

    public function edit(RawMaterial $rawMaterial): View
    {
        $categories = MaterialCategory::orderBy('name')->get();

        return view('raw-materials.edit', compact('rawMaterial', 'categories'));
    }

    public function update(RawMaterialRequest $request, RawMaterial $rawMaterial): RedirectResponse
    {
        $rawMaterial->update($request->validated());

        return redirect()->route('erp.raw-materials.index')->with('success', 'Raw material updated successfully.');
    }

    public function destroy(RawMaterial $rawMaterial): RedirectResponse
    {
        $rawMaterial->delete();

        return redirect()->route('erp.raw-materials.index')->with('success', 'Raw material deleted successfully.');
    }

    public function toggleStatus(RawMaterial $rawMaterial): RedirectResponse
    {
        $rawMaterial->update([
            'status' => $rawMaterial->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('erp.raw-materials.index')->with('success', 'Raw material status updated successfully.');
    }
}
