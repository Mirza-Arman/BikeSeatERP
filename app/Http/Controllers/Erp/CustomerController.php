<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request): void {
                $q->where('customer_code', 'like', '%'.$request->search.'%')
                    ->orWhere('customer_name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $customers = $query->latest()->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Auto-generate customer code if not provided
        if (empty($data['customer_code'])) {
            $lastCustomer = Customer::withTrashed()->orderBy('id', 'desc')->first();
            $lastId = $lastCustomer ? $lastCustomer->id : 0;
            $data['customer_code'] = 'CUS'.str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        }

        Customer::create($data);

        return redirect()->route('erp.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['orders' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()->route('erp.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('erp.customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function toggleStatus(Customer $customer): RedirectResponse
    {
        $customer->update([
            'status' => $customer->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()->route('erp.customers.index')->with('success', 'Customer status updated successfully.');
    }
}
