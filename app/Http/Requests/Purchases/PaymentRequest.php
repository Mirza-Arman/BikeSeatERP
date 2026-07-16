<?php

namespace App\Http\Requests\Purchases;

use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purchase_order_id' => ['required', 'exists:purchase_orders,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,bank_transfer,cheque'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'bank' => ['nullable', 'string', 'max:100'],
            'cheque_number' => ['nullable', 'string', 'max:100'],
            'payment_date' => ['required', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $purchaseOrder = PurchaseOrder::find($this->purchase_order_id);
            if ($purchaseOrder && $this->amount > $purchaseOrder->remaining_amount) {
                $validator->errors()->add('amount', 'Cannot pay more than remaining balance.');
            }
        });
    }
}
