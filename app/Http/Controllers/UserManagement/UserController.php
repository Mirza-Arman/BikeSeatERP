<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('user-management/users/index', 'Users', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'User Management'],
                ['label' => 'Users'],
            ]);
    }
}
