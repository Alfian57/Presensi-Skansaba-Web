<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\AttendanceConfig;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AttendanceService
{
    public function __construct(
        private QRCodeService $qrCodeService
    ) {}

    /**
     * Check in attendance.
     */
    public function checkIn(Student $student, string $qrCode, ?string $photoBase64 = null, ?array $location = null): array
    {
        // Validate QR code
        if (! $this->qrCodeService->validateCheckInQRCode($qrCode)) {
            return [
                'success' => false,
                'message' => 'QR Code tidak valid.',
                'data' => null,
            ];
        }

        $today = Carbon::today();

        // Check if already checked in today
        $existingAttendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini.',
                'data' => $existingAttendance,
            ];
        }

        // Determine status based on time
        $status = $this->determineCheckInStatus();
        $checkInTime = now();

        // Save photo if provided
        $photoPath = null;
        if ($photoBase64) {
            $photoPath = $this->saveAttendancePhoto($student->id, 'checkin', $photoBase64);
        }

        // Create attendance record
        $attendance = Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'status' => $status,
            'check_in_time' => $checkInTime,
            'check_in_photo' => $photoPath,
            'check_in_latitude' => $location['latitude'] ?? null,
            'check_in_longitude' => $location['longitude'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Check-in berhasil.',
            'data' => $attendance->fresh(),
        ];
    }

    /**
     * Check out attendance.
     */
    public function checkOut(Student $student, string $qrCode, ?string $photoBase64 = null, ?array $location = null): array
    {
        // Validate QR code
        if (! $this->qrCodeService->validateCheckOutQRCode($qrCode)) {
            return [
                'success' => false,
                'message' => 'QR Code tidak valid.',
                'data' => null,
            ];
        }

        $today = Carbon::today();

        // Check if already checked in today
        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        if (! $attendance) {
            return [
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini.',
                'data' => null,
            ];
        }

        if ($attendance->check_out_time) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini.',
                'data' => $attendance,
            ];
        }

        // Save photo if provided
        $photoPath = null;
        if ($photoBase64) {
            $photoPath = $this->saveAttendancePhoto($student->id, 'checkout', $photoBase64);
        }

        // Update attendance record
        $attendance->update([
            'check_out_time' => now(),
            'check_out_photo' => $photoPath,
            'check_out_latitude' => $location['latitude'] ?? null,
            'check_out_longitude' => $location['longitude'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Check-out berhasil.',
            'data' => $attendance->fresh(),
        ];
    }

    /**
     * Determine check-in status based on time.
     */
    private function determineCheckInStatus(): AttendanceStatus
    {
        $now = now();
        $lateThreshold = AttendanceConfig::getValue('late_threshold', '07:30');

        $lateTime = Carbon::parse($lateThreshold);

        if ($now->greaterThan($lateTime)) {
            return AttendanceStatus::LATE;
        }

        return AttendanceStatus::PRESENT;
    }

    /**
     * Save attendance photo from base64.
     */
    private function saveAttendancePhoto(int $studentId, string $type, string $base64): string
    {
        // Remove data:image/xxx;base64, prefix if exists
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $image = base64_decode($image);

        $filename = "attendance/{$studentId}/".date('Y-m-d')."_{$type}_".time().'.jpg';
        Storage::disk('public')->put($filename, $image);

        return $filename;
    }

    /**
     * Get student attendance summary.
     */
    public function getStudentSummary(Student $student, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = Attendance::where('student_id', $student->id);

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $attendances = $query->get();

        return [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', AttendanceStatus::PRESENT)->count(),
            'late' => $attendances->where('status', AttendanceStatus::LATE)->count(),
            'sick' => $attendances->where('status', AttendanceStatus::SICK)->count(),
            'permission' => $attendances->where('status', AttendanceStatus::PERMISSION)->count(),
            'absent' => $attendances->where('status', AttendanceStatus::ABSENT)->count(),
        ];
    }

    /**
     * Get classroom attendance summary.
     */
    public function getClassroomSummary(int $classroomId, Carbon $date): array
    {
        $students = Student::where('classroom_id', $classroomId)->get();
        $totalStudents = $students->count();

        $attendances = Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereDate('date', $date)
            ->get();

        return [
            'total_students' => $totalStudents,
            'total_present' => $attendances->whereIn('status', [AttendanceStatus::PRESENT, AttendanceStatus::LATE])->count(),
            'total_absent' => $totalStudents - $attendances->count(),
            'present' => $attendances->where('status', AttendanceStatus::PRESENT)->count(),
            'late' => $attendances->where('status', AttendanceStatus::LATE)->count(),
            'sick' => $attendances->where('status', AttendanceStatus::SICK)->count(),
            'permission' => $attendances->where('status', AttendanceStatus::PERMISSION)->count(),
            'absent' => $attendances->where('status', AttendanceStatus::ABSENT)->count(),
        ];
    }

    /**
     * Manually mark attendance (by teacher/admin).
     */
    public function markAttendance(Student $student, Carbon $date, AttendanceStatus $status, ?string $notes = null): Attendance
    {
        return Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'date' => $date->toDateString(),
            ],
            [
                'status' => $status,
                'notes' => $notes,
            ]
        );
    }

    /**
     * Update attendance status.
     */
    public function updateStatus(Attendance $attendance, string $status, ?string $notes = null): Attendance
    {
        $attendance->update([
            'status' => AttendanceStatus::from($status),
            'notes' => $notes,
        ]);

        return $attendance->fresh();
    }
}
