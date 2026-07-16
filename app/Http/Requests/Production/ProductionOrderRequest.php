<?php

namespace App\Http\Requests\Production;

use Illuminate\Foundation\Http\FormRequest;

class ProductionOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity_to_produce' => ['required', 'numeric', 'min:0.01'],
            'production_date' => ['required', 'date'],
            'remarks' => ['nullable', 'string'],
            'status' => ['nullable', 'in:pending,in_progress,completed'],
        ];
    }
}
