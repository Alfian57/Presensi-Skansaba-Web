<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->text('description')->nullable();
            $table->enum('type', ['national', 'school', 'regional'])->default('school');
            $table->boolean('is_recurring')->default(false); // Recurring annually
            $table->timestamps();
            $table->softDeletes();

            $table->index('date');
            $table->index(['date', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
