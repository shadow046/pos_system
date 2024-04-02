<?php

namespace App\Models;

use App\Traits\Models\Supports\UsesUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Receipt extends Model
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
        'path',
        'file_name',
    ];

    /**
     * Append attributes.
     */
    protected $appends = ['file'];

    /**
     * Receipt relationship with transaction.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Interact with the receipt's file path.
     */
    public function file(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk()->url($this->attributes['path']),
        );
    }
}
