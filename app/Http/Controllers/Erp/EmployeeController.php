<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\EmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::query()->with('department');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request): void {
                $q->where('employee_code', 'like', '%' . $request->search . '%')
                    ->orWhere('full_name', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query->latest()->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('employees.create', compact('departments'));
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Auto-generate employee code if not provided
        if (empty($data['employee_code'])) {
            $lastEmployee = Employee::withTrashed()->orderBy('id', 'desc')->first();
            $lastId = $lastEmployee ? $lastEmployee->id : 0;
            $data['employee_code'] = 'EMP' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        }
        
        $data['created_by'] = auth()->id();
        $employee = Employee::create($data);

        return redirect()->route('erp.employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()->route('erp.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('erp.employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function toggleStatus(Employee $employee): RedirectResponse
    {
        $employee->update([
            'status' => $employee->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('erp.employees.index')->with('success', 'Employee status updated successfully.');
    }
}
