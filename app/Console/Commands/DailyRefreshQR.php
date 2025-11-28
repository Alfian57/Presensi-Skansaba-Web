<?php

namespace App\Console\Commands;

use App\Services\QRCodeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyRefreshQR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh daily QR codes for attendance check-in and check-out';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(QRCodeService $qrCodeService)
    {
        try {
            $this->info('Refreshing QR codes...');

            // Refresh both QR codes
            $codes = $qrCodeService->refreshQRCodes();
            $this->line("Check-in QR: {$codes['check_in']}");
            $this->line("Check-out QR: {$codes['check_out']}");

            $this->info('QR codes refreshed successfully!');

            Log::info('QR codes refreshed', [
                'check_in' => $codes['check_in'],
                'check_out' => $codes['check_out'],
                'timestamp' => now()->toISOString(),
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to refresh QR codes: '.$e->getMessage());

            Log::error('QR refresh failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
