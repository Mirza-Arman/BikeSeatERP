<?php

namespace App\Http\Controllers\RawMaterials;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('raw-materials/categories/index', 'Categories', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Raw Material'],
                ['label' => 'Categories'],
            ]);
    }
}
