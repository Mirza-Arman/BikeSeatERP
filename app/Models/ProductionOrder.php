<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_no',
        'product_id',
        'formula_id',
        'quantity_to_produce',
        'production_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'production_date' => 'date',
        'quantity_to_produce' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function formula(): BelongsTo
    {
        return $this->belongsTo(ProductionFormula::class, 'formula_id');
    }

    public function workers(): HasMany
    {
        return $this->hasMany(ProductionWorker::class);
    }
}
