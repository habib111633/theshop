<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            // Product management
            'view products', 'create products', 'edit products', 'update products', 'delete products',
            // Order management
            'view orders', 'edit orders', 'update orders', 'delete orders',
            // User management
            'view users', 'create users', 'edit users', 'update users', 'delete users',
            // Category management
            'view categories', 'create categories', 'edit categories', 'update categories', 'delete categories',
            // Role management
            'view roles', 'create roles', 'edit roles', 'update roles', 'delete roles',
            // Conversation management
            'view conversations', 'create conversations', 'edit conversations', 'update conversations', 'delete conversations',
            // Dashboard
            'view dashboard',
            // General
            'place orders', 'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());

        $manager->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view orders', 'update orders',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view users',
            'view roles',
            'view conversations',
            'view dashboard',
        ]);

        $customer->givePermissionTo([
            'view products',
            'place orders',
         ]);

        // (Optional) Assign role to a user
        $user = \App\Models\User::find(1);
        $user?->assignRole('admin');

        // Assign 'customer' role to all users who have no role
        $usersWithoutRole = \App\Models\User::doesntHave('roles')->get();
        foreach ($usersWithoutRole as $user) {
            $user->assignRole('customer');
        }
    }
}
