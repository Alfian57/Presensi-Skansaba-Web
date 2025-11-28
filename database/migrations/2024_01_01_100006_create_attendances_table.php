<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'late', 'sick', 'permission', 'absent']);
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->text('notes')->nullable();
            $table->string('check_in_photo')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_id', 'date', 'deleted_at'], 'attendance_student_date_unique');
            $table->index(['date', 'status']);
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
