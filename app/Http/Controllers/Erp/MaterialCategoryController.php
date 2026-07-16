<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\RawMaterials\MaterialCategoryRequest;
use App\Models\MaterialCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = MaterialCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(15);

        return view('raw-materials.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('raw-materials.categories.create');
    }

    public function store(MaterialCategoryRequest $request): RedirectResponse
    {
        MaterialCategory::create($request->validated());

        return redirect()->route('erp.raw-materials.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(MaterialCategory $category): View
    {
        return view('raw-materials.categories.edit', compact('category'));
    }

    public function update(MaterialCategoryRequest $request, MaterialCategory $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('erp.raw-materials.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(MaterialCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('erp.raw-materials.categories.index')->with('success', 'Category deleted successfully.');
    }
}
