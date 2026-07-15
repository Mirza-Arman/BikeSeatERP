<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_code',
        'product_name',
        'model',
        'selling_price',
        'minimum_stock',
        'current_stock',
        'status',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }

    public function productionFormula(): HasOne
    {
        return $this->hasOne(ProductionFormula::class);
    }

    public function finishedGoodsTransactions(): HasMany
    {
        return $this->hasMany(FinishedGoodsTransaction::class);
    }

    public function customerOrderItems(): HasMany
    {
        return $this->hasMany(CustomerOrderItem::class);
    }
}
