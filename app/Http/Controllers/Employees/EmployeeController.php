<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('employees/index', 'Employees', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Employees'],
                ['label' => 'Employees'],
            ]);
    }
}
