<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'material_code',
        'name',
        'attributes',
        'unit',
        'minimum_stock',
        'current_stock',
        'cost_per_unit',
        'purchase_price',
        'average_cost',
        'description',
        'status',
    ];

    protected $casts = [
        'attributes' => 'array',
        'minimum_stock' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'average_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'category_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function formulaItems(): HasMany
    {
        return $this->hasMany(ProductionFormulaItem::class);
    }
}
