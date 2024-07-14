<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure that we have roles to associate users with
        Role::factory()->create();
        User::factory()->create();

        // // Create 10 sample users
        // for ($i = 0; $i < 10; $i++) {
        //     User::create([
        //         'name' => 'User ' . ($i + 1),
        //         'email' => 'user' . ($i + 1) . '@example.com',
        //         'role_id' => Role::inRandomOrder()->first()->id, // Assign a random role
        //         'phone' => '123456789' . $i,
        //         'password' => Hash::make('password'),
        //     ]);
        // }
    }
}
