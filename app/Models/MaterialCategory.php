<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialCategory extends Model
{
    use SoftDeletes;

    protected $table = 'material_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class, 'category_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(MaterialCategoryAttribute::class, 'category_id')->orderBy('sort_order');
    }
}
