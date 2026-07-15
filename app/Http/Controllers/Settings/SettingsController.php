<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('settings/index', 'Settings', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Settings'],
            ]);
    }
}
