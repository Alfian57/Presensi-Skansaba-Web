<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            AttendanceConfigSeeder::class,
            ClassroomSeeder::class,
            SubjectSeeder::class,
            UserSeeder::class,
            HomeroomSeeder::class,
            HolidaySeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Default admin credentials:');
        $this->command->info('Email: admin@skansaba.sch.id');
        $this->command->info('Password: password');
    }
}
