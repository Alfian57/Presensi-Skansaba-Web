<?php

namespace App\Console\Commands;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyMakeAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:create {--force : Force create even on holidays}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily attendance records for all active students';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $today = Carbon::now();

            // Check if today is a holiday
            if (! $this->option('force')) {
                $isHoliday = Holiday::whereDate('date', $today->toDateString())->exists();
                if ($isHoliday) {
                    $this->info('Today is a holiday. Skipping attendance creation.');
                    Log::info('Attendance creation skipped - Holiday', ['date' => $today->toDateString()]);

                    return Command::SUCCESS;
                }

                // Check if it's weekend (Sunday)
                if ($today->isSunday()) {
                    $this->info('Today is Sunday. Skipping attendance creation.');
                    Log::info('Attendance creation skipped - Sunday', ['date' => $today->toDateString()]);

                    return Command::SUCCESS;
                }
            }

            // Check if attendance already created for today
            $existingCount = Attendance::whereDate('date', $today->toDateString())->count();
            if ($existingCount > 0) {
                $this->warn("Attendance records already exist for today ({$existingCount} records).");
                if (! $this->confirm('Do you want to continue anyway?', false)) {
                    return Command::SUCCESS;
                }
            }

            // Get all active students
            $students = Student::where('is_active', true)->get();

            if ($students->isEmpty()) {
                $this->warn('No active students found.');
                Log::warning('No active students to create attendance');

                return Command::SUCCESS;
            }

            $this->info("Creating attendance records for {$students->count()} students...");
            $bar = $this->output->createProgressBar($students->count());

            $created = 0;
            foreach ($students as $student) {
                // Create absent record by default
                Attendance::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'date' => $today->toDateString(),
                    ],
                    [
                        'status' => AttendanceStatus::ABSENT,
                        'check_in' => null,
                        'check_out' => null,
                    ]
                );

                $created++;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();

            $this->info("Successfully created {$created} attendance records.");
            Log::info('Daily attendance created', [
                'date' => $today->toDateString(),
                'count' => $created,
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to create attendance: '.$e->getMessage());
            Log::error('Attendance creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
