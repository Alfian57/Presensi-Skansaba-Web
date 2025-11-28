<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, boolean, json, time
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // Can be accessed by API
            $table->timestamps();

            $table->index('key');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_configs');
    }
};
