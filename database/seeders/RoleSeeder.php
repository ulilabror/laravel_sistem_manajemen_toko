<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'Admin',
                'access_rights' => json_encode([
                    "view_users",
                    "view_user_details",
                    "create_users",
                    "edit_users",
                    "delete_users",
                    "view_user_points",
                    "view_user_files",
                    "view_user_transactions",
                    "view_roles",
                    "create_roles",
                    "view_role_details",
                    "edit_roles",
                    "delete_roles"
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'User',
                'access_rights' => json_encode([
                    "view_user_details",
                    "view_user_points",
                    "view_user_files",
                    "view_user_transactions"
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
