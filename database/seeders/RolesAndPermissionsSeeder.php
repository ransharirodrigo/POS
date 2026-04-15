<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // permissions
        $permissions = [
            'manage users',
            'manage sales',
            'view reports',
            'process payments',
            'category manage',
            'category add',
            'category update',
            'category delete',
            'category view',
            'product manage',
            'product add',
            'product update',
            'product delete',
            'product view',
            'customer manage',
            'customer add',
            'customer update',
            'customer delete',
            'customer view',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo(['manage sales', 'view reports', 'category manage', 'category add', 'category update', 'category delete', 'category view', 'customer manage', 'customer add', 'customer update', 'customer delete', 'customer view']);

        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->givePermissionTo(['manage sales', 'process payments', 'category view', 'customer add', 'customer view']);
    }
}
