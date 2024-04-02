<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'guard_name' => 'web',
        ];
    }

    /**
     * Admin role state
     *
     * @return static
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
        ]);
    }

    /**
     * Cashier role state
     *
     * @return static
     */
    public function cashier()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'cashier',
        ]);
    }
}
