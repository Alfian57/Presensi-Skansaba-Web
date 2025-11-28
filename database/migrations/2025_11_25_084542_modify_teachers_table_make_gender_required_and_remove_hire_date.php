<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First set a default value for existing null gender records
        DB::statement("UPDATE teachers SET gender = 'male' WHERE gender IS NULL");

        // Modify gender column to be NOT NULL using raw SQL
        DB::statement("ALTER TABLE teachers MODIFY COLUMN gender ENUM('male', 'female') NOT NULL");

        // Remove hire_date column
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('hire_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Make gender nullable again
            $table->enum('gender', ['male', 'female'])->nullable()->change();

            // Add back hire_date column
            $table->date('hire_date')->nullable()->after('address');
        });
    }
};
