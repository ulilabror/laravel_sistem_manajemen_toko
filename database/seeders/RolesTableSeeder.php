<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'role_name' => 'Admin',
                'access_rights' => json_encode(['create_user', 'edit_user', 'delete_user', 'view_reports']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'User',
                'access_rights' => json_encode(['view_reports']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Manager',
                'access_rights' => json_encode(['create_user', 'edit_user', 'view_reports']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
