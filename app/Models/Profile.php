<?php

namespace App\Models;

use App\Traits\Models\Supports\UsesUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
    ];

    /**
     * Profile relationship with users.
     */
    public function user(): BelongsTo
    {
        return $this->hasOne(User::class);
    }

    /**
     * Interact with the profile's full name.
     */
    public function fullName(): Attribute
    {
        if (blank($this->middle_name))
        {
            return Attribute::make(
                get: fn ($value, $attributes) => "{$attributes['first_name']} {$attributes['last_name']}",
            );
        }

        return Attribute::make(
            get: fn ($value, $attributes) => "{$attributes['first_name']} {$attributes['middle_name']} {$attributes['last_name']}",
        );
    }
}
