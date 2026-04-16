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
            'view sales',
            'view reports',
            'process payments',
            'staff manage',
            'staff add',
            'staff update',
            'staff delete',
            'staff view',
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
        $manager->givePermissionTo(['manage sales', 'view sales', 'view reports', 'staff manage', 'staff view', 'category manage', 'category add', 'category update', 'category delete', 'category view', 'product manage', 'product add', 'product update', 'product view', 'customer manage', 'customer add', 'customer update', 'customer delete', 'customer view']);

        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->givePermissionTo(['manage sales', 'view sales', 'process payments', 'category view', 'product view', 'customer add', 'customer view']);
    }
}
