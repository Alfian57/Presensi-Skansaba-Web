<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_number')->unique()->comment('NIP for civil servant teachers, employee ID for others');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('employee_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
