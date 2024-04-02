<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'action',
        'typeable_id',
        'typeable_type',
        'description',
        'device',
        'user_id',
    ];

    // Scope a query to only include activity logs per user.
    public function scopeByUser(Builder $query, int $user): Builder
    {
        return $query->where('user_id', $user);
    }

    // Scope a query to only include activity logs per action.
    public function scopeByAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }
}
