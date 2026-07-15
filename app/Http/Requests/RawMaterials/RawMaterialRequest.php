<?php

namespace App\Http\Requests\RawMaterials;

use Illuminate\Foundation\Http\FormRequest;

class RawMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:material_categories,id'],
            'material_code' => ['required', 'string', 'max:50', 'unique:raw_materials,material_code,' . ($this->route('rawMaterial')?->id ?? 0)],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'minimum_stock' => ['nullable', 'numeric', 'min:0'],
            'current_stock' => ['nullable', 'numeric', 'min:0'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
