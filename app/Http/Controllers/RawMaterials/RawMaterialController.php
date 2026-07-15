<?php

namespace App\Http\Controllers\RawMaterials;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class RawMaterialController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('raw-materials/index', 'Raw Materials', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Raw Material'],
                ['label' => 'Raw Materials'],
            ]);
    }
}
