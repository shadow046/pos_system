<?php

namespace App\Models;

use App\Traits\Models\Supports\UsesUuid;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
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
        'description',
        'price',
        'quantity',
        'image',
        'status'
    ];

    /**
     * Append attributes.
     */
    protected $appends = ['photo'];

    /**
     * Pivot relationship with category.
     * 
     * @return BelongsToMany
     */
    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class,
            'product_categories',
            'product_id',
            'category_id'
        );
    }

    /**
     * Interact with the product's photo.
     *
     * @return Attribute
     */
    public function photo() : Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk()->url($this->attributes['image']),
        );
    }

    // Check if product is available.
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    // Scope a query to only include available products.
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }
}
