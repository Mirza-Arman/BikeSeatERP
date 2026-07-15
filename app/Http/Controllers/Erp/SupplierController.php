<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request): void {
                $q->where('supplier_code', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $suppliers = $query->latest()->paginate(15);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Auto-generate supplier code if not provided
        if (empty($data['supplier_code'])) {
            $lastSupplier = Supplier::withTrashed()->orderBy('id', 'desc')->first();
            $lastId = $lastSupplier ? $lastSupplier->id : 0;
            $data['supplier_code'] = 'SUP' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        }
        
        Supplier::create($data);

        return redirect()->route('erp.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier): View
    {
        $supplier->load(['purchaseOrders' => function ($query) {
            $query->latest()->limit(10);
        }]);
        
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return redirect()->route('erp.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('erp.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    public function toggleStatus(Supplier $supplier): RedirectResponse
    {
        $supplier->update([
            'status' => $supplier->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('erp.suppliers.index')->with('success', 'Supplier status updated successfully.');
    }
}
