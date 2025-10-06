<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_day_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trip_day_id')
                ->constrained('trip_days')
                ->cascadeOnDelete();

            $table->uuid('student_id'); // UUID type for student_id, matching the 'id' in 'students' table
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
            // Creator polymorphic (openable-like)
            $table->nullableMorphs('creator');
            // => creator_id, creator_type

            $table->string('status')
                ->default('waiting')
                ->index();

            $table->timestamp('check_in_at')->nullable()->index();
            $table->timestamp('check_out_at')->nullable()->index();

            $table->decimal('check_in_lat', 10, 7)->nullable();
            $table->decimal('check_in_long', 10, 7)->nullable();
            $table->decimal('check_out_lat', 10, 7)->nullable();
            $table->decimal('check_out_long', 10, 7)->nullable();

            $table->string('source')->nullable();
            $table->text('notes')->nullable();

            $table->unique(['trip_day_id', 'student_id'], 'u_trip_day_student');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_day_attendances');
    }
};
