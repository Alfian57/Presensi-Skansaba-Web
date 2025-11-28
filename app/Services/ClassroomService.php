<?php

namespace App\Services;

use App\Models\Classroom;
use Illuminate\Support\Str;

class ClassroomService
{
    /**
     * Create a new classroom.
     */
    public function create(array $data): Classroom
    {
        return Classroom::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? Str::slug($data['name']),
            'grade_level' => $data['grade_level'],
            'major' => $data['major'] ?? null,
            'class_number' => $data['class_number'],
            'academic_year' => $data['academic_year'] ?? $this->getCurrentAcademicYear(),
        ]);
    }

    /**
     * Update classroom data.
     */
    public function update(Classroom $classroom, array $data): Classroom
    {
        $classroom->update([
            'name' => $data['name'] ?? $classroom->name,
            'slug' => isset($data['name']) ? Str::slug($data['name']) : $classroom->slug,
            'grade_level' => $data['grade_level'] ?? $classroom->grade_level,
            'major' => $data['major'] ?? $classroom->major,
            'class_number' => $data['class_number'] ?? $classroom->class_number,
            'academic_year' => $data['academic_year'] ?? $classroom->academic_year,
        ]);

        return $classroom->fresh();
    }

    /**
     * Delete classroom.
     */
    public function delete(Classroom $classroom): bool
    {
        return $classroom->delete();
    }

    /**
     * Get classrooms by grade level.
     */
    public function getByGradeLevel(int $gradeLevel)
    {
        return Classroom::where('grade_level', $gradeLevel)
            ->withCount('students')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();
    }

    /**
     * Get classrooms by major.
     */
    public function getByMajor(string $major)
    {
        return Classroom::where('major', $major)
            ->withCount('students')
            ->orderBy('grade_level')
            ->orderBy('class_number')
            ->get();
    }

    /**
     * Get current academic year.
     */
    public function getCurrentAcademicYear(): string
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Academic year starts in July (month 7)
        if ($currentMonth >= 7) {
            return $currentYear.'/'.($currentYear + 1);
        } else {
            return ($currentYear - 1).'/'.$currentYear;
        }
    }

    /**
     * Get classroom statistics.
     */
    public function getStatistics(Classroom $classroom): array
    {
        $totalStudents = $classroom->students()->count();
        $maleStudents = $classroom->students()->where('gender', 'male')->count();
        $femaleStudents = $classroom->students()->where('gender', 'female')->count();

        return [
            'total_students' => $totalStudents,
            'male_students' => $maleStudents,
            'female_students' => $femaleStudents,
            'homeroom_teacher' => $classroom->currentHomeroom?->teacher->user->name ?? '-',
        ];
    }
}
