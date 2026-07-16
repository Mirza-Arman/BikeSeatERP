<?php

namespace App\Http\Requests\Purchases;

use App\Models\PurchaseOrderItem;
use Illuminate\Foundation\Http\FormRequest;

class GoodsReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.purchase_order_item_id' => ['required', 'exists:purchase_order_items,id'],
            'items.*.received_quantity' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->items as $item) {
                $purchaseOrderItem = PurchaseOrderItem::find($item['purchase_order_item_id']);
                if ($purchaseOrderItem) {
                    $maxReceivable = $purchaseOrderItem->quantity - $purchaseOrderItem->received_quantity;
                    if ($item['received_quantity'] > $maxReceivable) {
                        $validator->errors()->add(
                            "items.{$item['purchase_order_item_id']}.received_quantity",
                            "Cannot receive more than {$maxReceivable} for this item."
                        );
                    }
                }
            }
        });
    }
}
