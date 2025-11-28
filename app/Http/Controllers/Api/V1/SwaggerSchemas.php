<?php

namespace App\Http\Controllers\Api\V1;

/**
 * @OA\Schema(
 *     schema="Student",
 *     type="object",
 *     title="Student",
 *     description="Student model",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="nisn", type="string", example="1234567890"),
 *     @OA\Property(property="nis", type="string", example="12345"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female"}, example="male"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="2005-01-15"),
 *     @OA\Property(property="phone", type="string", example="081234567890"),
 *     @OA\Property(property="address", type="string", example="Jl. Contoh No. 123"),
 *     @OA\Property(property="entry_year", type="integer", example=2023),
 *     @OA\Property(property="classroom", ref="#/components/schemas/Classroom"),
 *     @OA\Property(property="parent_name", type="string", example="Budi Santoso"),
 *     @OA\Property(property="parent_phone", type="string", example="081234567891"),
 *     @OA\Property(property="has_active_device", type="boolean", example=true),
 *     @OA\Property(property="device_registered_at", type="string", format="date-time", example="2024-01-01T10:00:00Z"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="username", type="string", example="johndoe"),
 *     @OA\Property(property="photo", type="string", nullable=true, example="https://example.com/storage/profiles/photo.jpg"),
 *     @OA\Property(property="role", type="string", example="student"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Classroom",
 *     type="object",
 *     title="Classroom",
 *     description="Classroom model",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="X IPA 1"),
 *     @OA\Property(property="grade_level", type="integer", example=10),
 *     @OA\Property(property="major", type="string", enum={"IPA", "IPS"}, example="IPA"),
 *     @OA\Property(property="class_number", type="integer", example=1),
 *     @OA\Property(property="academic_year", type="string", example="2023/2024"),
 *     @OA\Property(property="students_count", type="integer", example=30),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Attendance",
 *     type="object",
 *     title="Attendance",
 *     description="Attendance record",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="student", ref="#/components/schemas/Student"),
 *     @OA\Property(property="date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="check_in", type="string", format="time", example="07:30:00"),
 *     @OA\Property(property="check_out", type="string", format="time", nullable=true, example="15:00:00"),
 *     @OA\Property(property="status", type="string", enum={"present", "late", "sick", "permission", "absent"}, example="present"),
 *     @OA\Property(property="status_label", type="string", example="Hadir"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Tepat waktu"),
 *     @OA\Property(property="photo", type="string", nullable=true, example="https://example.com/storage/attendances/photo.jpg"),
 *     @OA\Property(property="latitude", type="number", format="float", nullable=true, example=-6.2088),
 *     @OA\Property(property="longitude", type="number", format="float", nullable=true, example=106.8456),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T07:30:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Schedule",
 *     type="object",
 *     title="Schedule",
 *     description="Class schedule",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="classroom", ref="#/components/schemas/Classroom"),
 *     @OA\Property(property="subject", ref="#/components/schemas/Subject"),
 *     @OA\Property(property="teacher", ref="#/components/schemas/Teacher"),
 *     @OA\Property(property="day", type="string", enum={"monday", "tuesday", "wednesday", "thursday", "friday", "saturday"}, example="monday"),
 *     @OA\Property(property="start_time", type="string", format="time", example="08:00"),
 *     @OA\Property(property="end_time", type="string", format="time", example="09:30"),
 *     @OA\Property(property="room", type="string", example="Lab Komputer 1"),
 *     @OA\Property(property="academic_year", type="string", example="2023/2024"),
 *     @OA\Property(property="semester", type="integer", enum={1, 2}, example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Subject",
 *     type="object",
 *     title="Subject",
 *     description="Subject/Course",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="code", type="string", example="MAT101"),
 *     @OA\Property(property="name", type="string", example="Matematika"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Mata pelajaran Matematika"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Teacher",
 *     type="object",
 *     title="Teacher",
 *     description="Teacher model",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="employee_number", type="string", example="198501012010011001"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="1985-01-01"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female"}, example="male"),
 *     @OA\Property(property="phone", type="string", example="081234567890"),
 *     @OA\Property(property="address", type="string", example="Jl. Guru No. 1"),
 *     @OA\Property(property="hire_date", type="string", format="date", example="2010-01-01"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     title="Error Response",
 *     description="Standard error response",
 *
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error message"),
 *     @OA\Property(property="errors", type="object", nullable=true, example={"field": {"Validation error message"}})
 * )
 *
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     type="object",
 *     title="Success Response",
 *     description="Standard success response",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Operation successful"),
 *     @OA\Property(property="data", type="object", nullable=true)
 * )
 */
class SwaggerSchemas
{
    // This class is only for Swagger schema definitions
}
