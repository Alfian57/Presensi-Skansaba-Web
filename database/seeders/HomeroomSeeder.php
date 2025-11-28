<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Homeroom;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class HomeroomSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = Classroom::all();
        $teachers = Teacher::all();

        if ($teachers->count() === 0) {
            $this->command->warn('No teachers found. Skipping homeroom seeder.');

            return;
        }

        foreach ($classrooms as $index => $classroom) {
            // Assign one teacher per classroom as homeroom teacher
            $teacher = $teachers[$index % $teachers->count()];

            Homeroom::create([
                'teacher_id' => $teacher->id,
                'classroom_id' => $classroom->id,
                'academic_year' => '2024/2025',
                'is_active' => true,
            ]);
        }
    }
}
