<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = [
            // Grade 10
            ['name' => '10 IPA 1', 'grade_level' => '10', 'major' => 'IPA', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 IPA 2', 'grade_level' => '10', 'major' => 'IPA', 'class_number' => 2, 'capacity' => 36],
            ['name' => '10 IPS 1', 'grade_level' => '10', 'major' => 'IPS', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 IPS 2', 'grade_level' => '10', 'major' => 'IPS', 'class_number' => 2, 'capacity' => 36],

            // Grade 11
            ['name' => '11 IPA 1', 'grade_level' => '11', 'major' => 'IPA', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 IPA 2', 'grade_level' => '11', 'major' => 'IPA', 'class_number' => 2, 'capacity' => 36],
            ['name' => '11 IPS 1', 'grade_level' => '11', 'major' => 'IPS', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 IPS 2', 'grade_level' => '11', 'major' => 'IPS', 'class_number' => 2, 'capacity' => 36],

            // Grade 12
            ['name' => '12 IPA 1', 'grade_level' => '12', 'major' => 'IPA', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 IPA 2', 'grade_level' => '12', 'major' => 'IPA', 'class_number' => 2, 'capacity' => 36],
            ['name' => '12 IPS 1', 'grade_level' => '12', 'major' => 'IPS', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 IPS 2', 'grade_level' => '12', 'major' => 'IPS', 'class_number' => 2, 'capacity' => 36],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }
    }
}
