<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        $employee = Employee::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'role' => 'super admin',
            'phone' => '0761212041',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $employee->assignRole('super admin');
    }
}