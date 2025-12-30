<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => Role::ADMIN,
                'description' => 'Full system access',
            ],
            [
                'name' => 'Store Manager',
                'slug' => Role::STORE_MANAGER,
                'description' => 'Manage purchasing and inventory',
            ],
            [
                'name' => 'Cashier',
                'slug' => Role::CASHIER,
                'description' => 'Perform POS sales only',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
