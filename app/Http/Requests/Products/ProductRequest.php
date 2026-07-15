<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_code' => ['required', 'string', 'max:50', 'unique:products,product_code,' . ($this->route('product')?->id ?? 0)],
            'product_name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'minimum_stock' => ['nullable', 'numeric', 'min:0'],
            'current_stock' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
