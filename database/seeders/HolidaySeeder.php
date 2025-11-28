<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            // National Holidays 2024-2025
            ['name' => 'Tahun Baru 2025', 'date' => '2025-01-01', 'type' => 'national', 'is_recurring' => true],
            ['name' => 'Imlek 2575 Kongzili', 'date' => '2025-01-29', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Isra Mikraj', 'date' => '2025-02-07', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Hari Raya Nyepi', 'date' => '2025-03-29', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Wafat Isa Almasih', 'date' => '2025-04-18', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Hari Buruh', 'date' => '2025-05-01', 'type' => 'national', 'is_recurring' => true],
            ['name' => 'Kenaikan Isa Almasih', 'date' => '2025-05-29', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Hari Raya Idul Fitri 1446 H', 'date' => '2025-03-30', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Cuti Bersama Idul Fitri', 'date' => '2025-03-31', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Hari Raya Waisak', 'date' => '2025-05-12', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Hari Lahir Pancasila', 'date' => '2025-06-01', 'type' => 'national', 'is_recurring' => true],
            ['name' => 'Hari Raya Idul Adha', 'date' => '2025-06-07', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Tahun Baru Islam', 'date' => '2025-06-27', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Hari Kemerdekaan RI', 'date' => '2025-08-17', 'type' => 'national', 'is_recurring' => true],
            ['name' => 'Maulid Nabi Muhammad', 'date' => '2025-09-05', 'type' => 'national', 'is_recurring' => false],
            ['name' => 'Hari Raya Natal', 'date' => '2025-12-25', 'type' => 'national', 'is_recurring' => true],

            // School Holidays
            ['name' => 'Libur Semester Ganjil', 'date' => '2024-12-23', 'type' => 'school', 'is_recurring' => false],
            ['name' => 'Libur Semester Genap', 'date' => '2025-06-23', 'type' => 'school', 'is_recurring' => false],
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}
