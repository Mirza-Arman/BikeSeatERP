<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_code' => ['nullable', 'string', 'max:50'],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email,' . ($this->route('customer')?->id ?? 0)],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'balance' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
