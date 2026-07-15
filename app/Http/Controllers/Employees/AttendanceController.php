<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('employees/attendance/index', 'Attendance', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Employees'],
                ['label' => 'Attendance'],
            ]);
    }
}
