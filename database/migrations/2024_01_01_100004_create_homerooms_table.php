<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('homerooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->string('academic_year'); // e.g., 2024/2025
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['classroom_id', 'academic_year', 'deleted_at'], 'homeroom_classroom_year_unique');
            $table->index(['teacher_id', 'academic_year']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homerooms');
    }
};
