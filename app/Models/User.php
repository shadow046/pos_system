<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Models\Supports\UsesUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'otp',
        'otp_expired_at',
        'device_verified_at',
        'email',
        'password',
        'current_ip_address',
        'has_changed_password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'deactivated_at' => 'datetime',
        'otp_expired_at' => 'datetime',
        'device_verified_at' => 'datetime',
    ];

    /**
     * User relationship with profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Interact with the user's email.
     */
    public function maskedEmail(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->processMasking($attributes['email']),
        );
    }

    // Process masking email.
    protected function processMasking(string $email): string
    {
        $domain = explode('@', $email);
        $name = implode('@', array_slice($domain, 0, count($domain) - 1));
        $len = floor(strlen($name) / 2);

        return substr($name, 0, 1).str_repeat('*', $len).'@'.end($domain);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(['admin']);
    }

    /**
     * Check if user is cashier.
     */
    public function isCashier(): bool
    {
        return $this->hasRole(['cashier']);
    }
}
