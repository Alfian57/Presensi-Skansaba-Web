<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Student Management
            'view students',
            'create students',
            'edit students',
            'delete students',
            'export students',

            // Teacher Management
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',
            'export teachers',

            // Classroom Management
            'view classrooms',
            'create classrooms',
            'edit classrooms',
            'delete classrooms',

            // Subject Management
            'view subjects',
            'create subjects',
            'edit subjects',
            'delete subjects',

            // Schedule Management
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',
            'view own schedules',

            // Attendance Management
            'view attendances',
            'create attendances',
            'edit attendances',
            'delete attendances',
            'export attendances',
            'view own attendance',
            'recap attendances',

            // Class Absence Management
            'view class absences',
            'create class absences',
            'edit class absences',
            'delete class absences',
            'export class absences',

            // Homeroom Management
            'view homerooms',
            'create homerooms',
            'edit homerooms',
            'delete homerooms',

            // Configuration Management
            'view configs',
            'edit configs',

            // Holiday Management
            'view holidays',
            'create holidays',
            'edit holidays',
            'delete holidays',

            // Reports
            'view reports',
            'export reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions

        // Admin Role - Full access
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        // Teacher Role - Limited access
        $teacher = Role::create(['name' => 'teacher', 'guard_name' => 'web']);
        $teacher->givePermissionTo([
            'view students',
            'view classrooms',
            'view subjects',
            'view schedules',
            'view own schedules',
            'view attendances',
            'edit attendances',
            'view class absences',
            'create class absences',
            'edit class absences',
            'delete class absences',
            'view homerooms',
            'view reports',
        ]);

        // Student Role - Very limited access
        $student = Role::create(['name' => 'student', 'guard_name' => 'web']);
        $student->givePermissionTo([
            'view own schedules',
            'view own attendance',
            'create attendances', // For self check-in/check-out
        ]);
    }
}
