<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('user-management/permissions/index', 'Permissions', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'User Management'],
                ['label' => 'Permissions'],
            ]);
    }
}
