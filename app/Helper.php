<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Helper
{
    /**
     * Add navigation history to session (breadcrumb trail).
     */
    public static function addHistory(string $route, string $name, int $maxHistory = 5): void
    {
        $history = session('history', []);

        // Don't add if it's the same as the last page
        if (! empty($history) && end($history)['name'] === $name) {
            return;
        }

        // Keep only last n items
        if (count($history) >= $maxHistory) {
            array_shift($history);
        }

        $history[] = [
            'route' => $route,
            'name' => $name,
            'timestamp' => now()->toISOString(),
        ];

        session()->put('history', $history);
    }

    /**
     * Clear navigation history.
     */
    public static function clearHistory(): void
    {
        session()->forget('history');
    }

    /**
     * Get navigation history.
     */
    public static function getHistory(): array
    {
        return session('history', []);
    }

    /**
     * Format Indonesian date.
     */
    public static function formatIndonesianDate(?Carbon $date, string $format = 'd F Y'): string
    {
        if (! $date) {
            return '-';
        }

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $formatted = $date->format($format);

        // Replace English month names with Indonesian
        foreach ($months as $num => $name) {
            $formatted = str_replace($date->format('F'), $name, $formatted);
        }

        // Replace English day names with Indonesian
        $formatted = str_replace(array_keys($days), array_values($days), $formatted);

        return $formatted;
    }

    /**
     * Format time ago in Indonesian.
     */
    public static function timeAgoIndonesian(?Carbon $date): string
    {
        if (! $date) {
            return '-';
        }

        $diff = $date->diff(now());

        if ($diff->y > 0) {
            return $diff->y.' tahun yang lalu';
        } elseif ($diff->m > 0) {
            return $diff->m.' bulan yang lalu';
        } elseif ($diff->d > 0) {
            return $diff->d.' hari yang lalu';
        } elseif ($diff->h > 0) {
            return $diff->h.' jam yang lalu';
        } elseif ($diff->i > 0) {
            return $diff->i.' menit yang lalu';
        } else {
            return 'Baru saja';
        }
    }

    /**
     * Generate random string.
     */
    public static function generateCode(int $length = 10): string
    {
        return Str::random($length);
    }

    /**
     * Format file size.
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2).' '.$units[$pow];
    }

    /**
     * Truncate text with ellipsis.
     */
    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        return Str::limit($text, $length, $suffix);
    }

    /**
     * Get initials from name.
     */
    public static function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            if (! empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }

        return substr($initials, 0, 2);
    }
}
