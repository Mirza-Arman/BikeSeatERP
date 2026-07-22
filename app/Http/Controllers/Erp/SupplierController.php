<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierPaymentRequest;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    private SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index(Request $request): View
    {
        $query = Supplier::with('rawMaterials');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request): void {
                $q->where('supplier_code', 'like', '%' . $request->search . '%')
                    ->orWhere('company_name', 'like', '%' . $request->search . '%')
                    ->orWhere('contact_person', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suppliers = $query->latest()->paginate(15);
        $statistics = $this->supplierService->getSupplierStatistics();

        return view('suppliers.index', compact('suppliers', 'statistics'));
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['supplier_code'] = $this->supplierService->generateSupplierCode();
        $data['balance'] = 0;

        Supplier::create($data);

        return redirect()->route('erp.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier): View
    {
        $dashboard = $this->supplierService->getSupplierDashboard($supplier);

        return view('suppliers.show', compact('supplier', 'dashboard'));
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

    public function ledger(Supplier $supplier): View
    {
        $ledger = $this->supplierService->getSupplierLedger($supplier);

        return view('suppliers.ledger', compact('supplier', 'ledger'));
    }

    public function materialsSupplied(Supplier $supplier): View
    {
        $materials = $this->supplierService->getMaterialsSupplied($supplier);

        return view('suppliers.materials-supplied', compact('supplier', 'materials'));
    }

    public function createPayment(Supplier $supplier): View
    {
        return view('suppliers.create-payment', compact('supplier'));
    }

    public function storePayment(SupplierPaymentRequest $request): RedirectResponse
    {
        $this->supplierService->recordSupplierPayment(
            $request->validated(),
            auth()->id()
        );

        return redirect()->route('erp.suppliers.show', $request->supplier_id)
            ->with('success', 'Payment recorded successfully.');
    }
}
