<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => fake()->unique()->freeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'status' => 'active',
            'has_changed_password' => true,
            'device_verified_at' => now(),
            'current_ip_address' => request()->ip(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Profile state
     *
     * @return static
     */
    public function profile()
    {
        return $this->state(fn (array $attributes) => [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
        ]);
    }

    // Admin
    public function admin()
    {
        $user = $this->create();

        $user->assignRole('admin');

        return $user;
    }

    /**
     * Indicate that the model's device should be unverified.
     *
     * @return static
     */
    public function unverifiedDevice()
    {
        return $this->state(fn (array $attributes) => [
            'device_verified_at' => null,
            'current_ip_address' => null,
        ]);
    }
}
