<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Add new columns
            $table->json('classrooms_ar')->nullable();
            $table->json('classrooms_en')->nullable();
        });

        // Copy existing data (if any)
        DB::statement('UPDATE grades SET classrooms_ar = default_classrooms_ar');
        DB::statement('UPDATE grades SET classrooms_en = default_classrooms');

        Schema::table('grades', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn('default_classrooms_ar');
            $table->dropColumn('default_classrooms');
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->json('default_classrooms_ar')->nullable();
            $table->json('default_classrooms')->nullable();
        });

        DB::statement('UPDATE grades SET default_classrooms_ar = classrooms_ar');
        DB::statement('UPDATE grades SET default_classrooms = classrooms_en');

        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('classrooms_ar');
            $table->dropColumn('classrooms_en');
        });
    }
};

