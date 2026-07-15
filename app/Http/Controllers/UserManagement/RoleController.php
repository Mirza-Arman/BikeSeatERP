<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('user-management/roles/index', 'Roles', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'User Management'],
                ['label' => 'Roles'],
            ]);
    }
}
