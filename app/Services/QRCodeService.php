<?php

namespace App\Services;

use App\Models\AttendanceConfig;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
    /**
     * Generate QR code for attendance check-in.
     */
    public function generateCheckInQRCode(): string
    {
        $code = AttendanceConfig::getValue('qr_code_checkin');

        if (! $code) {
            $code = $this->generateRandomCode();
            AttendanceConfig::setValue('qr_code_checkin', $code, 'string');
        }

        return $code;
    }

    /**
     * Generate QR code for attendance check-out.
     */
    public function generateCheckOutQRCode(): string
    {
        $code = AttendanceConfig::getValue('qr_code_checkout');

        if (! $code) {
            $code = $this->generateRandomCode();
            AttendanceConfig::setValue('qr_code_checkout', $code, 'string');
        }

        return $code;
    }

    /**
     * Refresh both QR codes.
     */
    public function refreshQRCodes(): array
    {
        $checkInCode = $this->generateRandomCode();
        $checkOutCode = $this->generateRandomCode();

        AttendanceConfig::setValue('qr_code_checkin', $checkInCode, 'string');
        AttendanceConfig::setValue('qr_code_checkout', $checkOutCode, 'string');

        // Clear cache
        Cache::forget('attendance_config_qr_code_checkin');
        Cache::forget('attendance_config_qr_code_checkout');

        return [
            'check_in' => $checkInCode,
            'check_out' => $checkOutCode,
        ];
    }

    /**
     * Validate QR code for check-in.
     */
    public function validateCheckInQRCode(string $code): bool
    {
        $validCode = AttendanceConfig::getValue('qr_code_checkin');

        return $code === $validCode;
    }

    /**
     * Validate QR code for check-out.
     */
    public function validateCheckOutQRCode(string $code): bool
    {
        $validCode = AttendanceConfig::getValue('qr_code_checkout');

        return $code === $validCode;
    }

    /**
     * Generate QR code image (SVG).
     */
    public function generateQRImage(string $code, int $size = 300): string
    {
        return QrCode::size($size)
            ->margin(2)
            ->generate($code);
    }

    /**
     * Get QR code data with images.
     */
    public function getQRCodesWithImages(int $size = 300): array
    {
        $checkInCode = $this->generateCheckInQRCode();
        $checkOutCode = $this->generateCheckOutQRCode();

        return [
            'check_in' => [
                'code' => $checkInCode,
                'image' => $this->generateQRImage($checkInCode, $size),
            ],
            'check_out' => [
                'code' => $checkOutCode,
                'image' => $this->generateQRImage($checkOutCode, $size),
            ],
        ];
    }

    /**
     * Generate random code for QR.
     */
    private function generateRandomCode(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }
}
