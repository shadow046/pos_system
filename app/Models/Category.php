<?php

namespace App\Models;

use App\Traits\Models\Supports\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Pivot relationship with product.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,
            'product_categories',
            'category_id',
            'product_id'
        );
    }
}
