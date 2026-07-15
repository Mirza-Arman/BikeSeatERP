<?php

namespace App\Http\Requests\RawMaterials;

use Illuminate\Foundation\Http\FormRequest;

class MaterialCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:material_categories,name,' . ($this->route('category')?->id ?? 0)],
            'description' => ['nullable', 'string'],
        ];
    }
}
