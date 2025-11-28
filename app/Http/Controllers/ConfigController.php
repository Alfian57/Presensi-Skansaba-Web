<?php

namespace App\Http\Controllers;

use App\Models\AttendanceConfig;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ConfigController extends Controller
{
    public function __construct(
        private QRCodeService $qrCodeService
    ) {}

    /**
     * Display system configuration.
     */
    public function index()
    {
        $configs = AttendanceConfig::all()->keyBy('key');

        return view('config.index', compact('configs'));
    }

    /**
     * Update system configuration.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'check_in_start' => 'required|date_format:H:i',
            'check_in_end' => 'required|date_format:H:i',
            'late_threshold' => 'required|date_format:H:i',
            'check_out_start' => 'required|date_format:H:i',
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string',
            'school_phone' => 'nullable|string|max:20',
            'academic_year' => 'required|string|max:20',
        ]);

        try {
            foreach ($validated as $key => $value) {
                AttendanceConfig::where('key', $key)->update(['value' => $value]);
            }

            Alert::success('Berhasil', 'Konfigurasi sistem berhasil diperbarui.');

            return redirect()->route('dashboard.config.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Refresh QR codes.
     */
    public function refreshQR()
    {
        try {
            $checkInCode = $this->qrCodeService->generateCheckInQRCode();
            $checkOutCode = $this->qrCodeService->generateCheckOutQRCode();

            AttendanceConfig::where('key', 'qr_check_in')->update(['value' => $checkInCode]);
            AttendanceConfig::where('key', 'qr_check_out')->update(['value' => $checkOutCode]);

            Alert::success('Berhasil', 'QR Code berhasil diperbarui.');

            return back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());

            return back();
        }
    }

    /**
     * Display QR codes for public view.
     */
    public function displayQR()
    {
        $checkInQR = AttendanceConfig::where('key', 'qr_check_in')->first();
        $checkOutQR = AttendanceConfig::where('key', 'qr_check_out')->first();

        return view('config.qr-display', compact('checkInQR', 'checkOutQR'));
    }
}
