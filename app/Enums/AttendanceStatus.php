<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case LATE = 'late';
    case SICK = 'sick';
    case PERMISSION = 'permission';
    case ABSENT = 'absent';

    public function label(): string
    {
        return match ($this) {
            self::PRESENT => 'Hadir',
            self::LATE => 'Terlambat',
            self::SICK => 'Sakit',
            self::PERMISSION => 'Izin',
            self::ABSENT => 'Alpha',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PRESENT => 'success',
            self::LATE => 'warning',
            self::SICK => 'info',
            self::PERMISSION => 'primary',
            self::ABSENT => 'danger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
