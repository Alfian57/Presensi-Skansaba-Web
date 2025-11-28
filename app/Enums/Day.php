<?php

namespace App\Enums;

enum Day: string
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public function label(): string
    {
        return match ($this) {
            self::MONDAY => 'Senin',
            self::TUESDAY => 'Selasa',
            self::WEDNESDAY => 'Rabu',
            self::THURSDAY => 'Kamis',
            self::FRIDAY => 'Jumat',
            self::SATURDAY => 'Sabtu',
            self::SUNDAY => 'Minggu',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::MONDAY => 'Sen',
            self::TUESDAY => 'Sel',
            self::WEDNESDAY => 'Rab',
            self::THURSDAY => 'Kam',
            self::FRIDAY => 'Jum',
            self::SATURDAY => 'Sab',
            self::SUNDAY => 'Min',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function schoolDays(): array
    {
        return [
            self::MONDAY,
            self::TUESDAY,
            self::WEDNESDAY,
            self::THURSDAY,
            self::FRIDAY,
            self::SATURDAY,
        ];
    }
}
