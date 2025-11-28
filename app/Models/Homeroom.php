<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homeroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'academic_year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentAcademicYear($query)
    {
        $currentYear = config('attendance.academic_year', date('Y'));

        return $query->where('academic_year', $currentYear);
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }
}
