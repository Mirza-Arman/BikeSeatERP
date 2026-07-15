<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class WorkerController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('production/workers/index', 'Workers', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Production'],
                ['label' => 'Workers'],
            ]);
    }
}
