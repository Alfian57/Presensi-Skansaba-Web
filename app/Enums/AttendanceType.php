<?php

namespace App\Enums;

enum AttendanceType: string
{
    case CHECK_IN = 'check_in';
    case CHECK_OUT = 'check_out';

    public function label(): string
    {
        return match ($this) {
            self::CHECK_IN => 'Presensi Masuk',
            self::CHECK_OUT => 'Presensi Pulang',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
