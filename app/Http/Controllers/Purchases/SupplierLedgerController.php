<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Services\SupplierLedgerService;

class SupplierLedgerController extends Controller
{
    private SupplierLedgerService $ledgerService;

    public function __construct(SupplierLedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
    }

    public function index()
    {
        $suppliersOutstanding = $this->ledgerService->getAllSuppliersOutstanding();

        return view('purchases.supplier-ledger.index', compact('suppliersOutstanding'));
    }

    public function show(Supplier $supplier)
    {
        $ledger = $this->ledgerService->getSupplierLedger($supplier->id);
        $summary = $this->ledgerService->getSupplierSummary($supplier->id);

        return view('purchases.supplier-ledger.show', compact('supplier', 'ledger', 'summary'));
    }
}
