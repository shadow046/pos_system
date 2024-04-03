<?php

namespace App\Models;

use App\Traits\Models\Supports\UsesUuid;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_type',
        'receipt_no',
        'user_id',
        'sold_at',
        'total_order',
        'sales',
        'discount',
        'vat',
        'amount',
        'amount_paid',
        'change',
        'status',
    ];

    /**
     * Transaction relationship with orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Transaction relationship with user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaction relationship with receipt.
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    // Scope a query to only include pending transactions.
    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'preparing', 'serving']);
    }

    // Scope a query to only include completed transactions.
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    // Scope a query to only include void transactions.
    public function scopeVoid(Builder $query): Builder
    {
        return $query->where('status', 'void');
    }

    // Scope a query to only include transaction between dates.
    public function scopeBetweenDate(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('sold_at', [$from, $to]);
    }

    // Scope a query to only exclude void transactions.
    public function scopeNotVoid(Builder $query): Builder
    {
        return $query->where('status', '!=', 'void');
    }

    // Scope a query to only include transactions per status.
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    // Scope a query to only include todays transaction.
    public function scopeToday(Builder $query): Builder
    {
        $from = now()->format('Y-m-d').' 00:00:00';
        $to = now()->format('Y-m-d').' 23:59:59';

        return $query->betweenDate($from, $to);
    }
}
