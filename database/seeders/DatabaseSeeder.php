<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Role::create(['name' => 'admin', 'guard_name' => 'web']);

        // $user = \App\Models\User::factory()->create([
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('secret'),
        //     'status' => 'active'
        // ]);

        // \App\Models\Profile::create(['first_name' => 'Default', 'last_name' => 'Admin', 'user_id' => $user->id]);

        // $user->assignRole('admin');
    }
}
