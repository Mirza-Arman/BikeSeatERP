<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'order_date',
        'invoice_no',
        'grand_total',
        'paid_amount',
        'remaining_amount',
        'status',
    ];

    protected $casts = [
        'order_date' => 'date',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CustomerOrderItem::class, 'order_id');
    }
}
