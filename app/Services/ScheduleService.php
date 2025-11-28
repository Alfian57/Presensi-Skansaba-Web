<?php

namespace App\Services;

use App\Enums\Day;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleService
{
    /**
     * Create a new schedule.
     */
    public function create(array $data): Schedule
    {
        return Schedule::create([
            'classroom_id' => $data['classroom_id'],
            'subject_id' => $data['subject_id'],
            'teacher_id' => $data['teacher_id'],
            'day' => $data['day'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'room' => $data['room'] ?? null,
            'academic_year' => $data['academic_year'] ?? $this->getCurrentAcademicYear(),
            'semester' => $data['semester'] ?? $this->getCurrentSemester(),
        ]);
    }

    /**
     * Update schedule.
     */
    public function update(Schedule $schedule, array $data): Schedule
    {
        $schedule->update([
            'classroom_id' => $data['classroom_id'] ?? $schedule->classroom_id,
            'subject_id' => $data['subject_id'] ?? $schedule->subject_id,
            'teacher_id' => $data['teacher_id'] ?? $schedule->teacher_id,
            'day' => $data['day'] ?? $schedule->day,
            'start_time' => $data['start_time'] ?? $schedule->start_time,
            'end_time' => $data['end_time'] ?? $schedule->end_time,
            'room' => $data['room'] ?? $schedule->room,
            'academic_year' => $data['academic_year'] ?? $schedule->academic_year,
            'semester' => $data['semester'] ?? $schedule->semester,
        ]);

        return $schedule->fresh(['classroom', 'subject', 'teacher.user']);
    }

    /**
     * Delete schedule.
     */
    public function delete(Schedule $schedule): bool
    {
        return $schedule->delete();
    }

    /**
     * Get schedules by classroom.
     */
    public function getByClassroom(int $classroomId, ?Day $day = null)
    {
        $query = Schedule::where('classroom_id', $classroomId)
            ->with(['subject', 'teacher.user', 'classroom']);

        if ($day) {
            $query->where('day', $day);
        }

        return $query->orderBy('day')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get schedules by teacher.
     */
    public function getByTeacher(int $teacherId, ?Day $day = null)
    {
        $query = Schedule::where('teacher_id', $teacherId)
            ->with(['subject', 'classroom', 'teacher.user']);

        if ($day) {
            $query->where('day', $day);
        }

        return $query->orderBy('day')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get today's schedules for classroom.
     */
    public function getTodaySchedules(int $classroomId)
    {
        $today = Day::from(strtolower(Carbon::now()->format('l')));

        return $this->getByClassroom($classroomId, $today);
    }

    /**
     * Check schedule conflict.
     */
    public function hasConflict(int $teacherId, Day $day, string $startTime, string $endTime, ?int $excludeScheduleId = null): bool
    {
        $query = Schedule::where('teacher_id', $teacherId)
            ->where('day', $day)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        return $query->exists();
    }

    /**
     * Get current academic year.
     */
    public function getCurrentAcademicYear(): string
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        if ($currentMonth >= 7) {
            return $currentYear.'/'.($currentYear + 1);
        } else {
            return ($currentYear - 1).'/'.$currentYear;
        }
    }

    /**
     * Get current semester.
     */
    public function getCurrentSemester(): int
    {
        $currentMonth = now()->month;

        // Semester 1: July - December
        // Semester 2: January - June
        return $currentMonth >= 7 ? 1 : 2;
    }
}
