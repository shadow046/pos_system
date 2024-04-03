<?php

namespace App\Models;

use App\Traits\Models\Supports\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Order relationship with product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
