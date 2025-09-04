<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'admin'
        ]);
        
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'admin'
        ]);
        
        $moderatorRole = Role::firstOrCreate([
            'name' => 'moderator',
            'guard_name' => 'admin'
        ]);

        // Create permissions
        $permissions = [
            'view-dashboard',
            'manage-villas',
            'manage-bookings',
            'manage-users',
            'manage-payments',
            'view-reports',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Assign all permissions to super-admin
        $superAdminRole->givePermissionTo(Permission::where('guard_name', 'admin')->get());
        
        // Assign specific permissions to admin
        $adminRole->givePermissionTo([
            'view-dashboard',
            'manage-villas',
            'manage-bookings',
            'view-reports'
        ]);
        
        // Assign specific permissions to moderator
        $moderatorRole->givePermissionTo([
            'view-dashboard',
            'manage-bookings'
        ]);

        // Create super admin user
        $superAdmin = AdminUser::firstOrCreate(
            ['email' => 'admin@villakiralama.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }
    }
}
