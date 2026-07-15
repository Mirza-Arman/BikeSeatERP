<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('employees/departments/index', 'Departments', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Employees'],
                ['label' => 'Departments'],
            ]);
    }
}
