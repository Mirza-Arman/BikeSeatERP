<?php

namespace App\Http\Controllers\RawMaterials;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\StockTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function index(Request $request): View
    {
        $rawMaterials = RawMaterial::where('status', 'active')->get();

        return view('raw-materials.stock-adjustments.index', compact('rawMaterials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'type' => 'required|in:add,subtract',
            'quantity' => 'required|numeric|min:0.01',
            'adjustment_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $material = RawMaterial::findOrFail($request->raw_material_id);
        
        if ($request->type === 'add') {
            $material->current_stock += $request->quantity;
        } else {
            if ($material->current_stock < $request->quantity) {
                return back()->with('error', 'Insufficient stock for this adjustment.');
            }
            $material->current_stock -= $request->quantity;
        }
        
        $material->save();

        // Create stock transaction record
        StockTransaction::create([
            'raw_material_id' => $material->id,
            'type' => $request->type === 'add' ? 'in' : 'out',
            'quantity' => $request->quantity,
            'balance_after' => $material->current_stock,
            'transaction_date' => $request->adjustment_date,
            'reference' => 'Manual Adjustment: ' . ($request->reason ?? 'No reason'),
        ]);

        return redirect()->route('erp.raw-materials.stock-ledger.index')
            ->with('success', 'Stock adjustment saved successfully.');
    }
}
