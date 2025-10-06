<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_day_attendants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trip_day_id')
                ->constrained('trip_days')
                ->cascadeOnDelete();

            $table->foreignId('attendant_id')
                ->constrained('attendants')
                ->cascadeOnDelete();

            $table->timestamp('joined_at')->nullable()->index();
            $table->timestamp('left_at')->nullable()->index();

            $table->tinyInteger('active')
                ->storedAs('CASE WHEN `left_at` IS NULL THEN 1 ELSE 0 END');

            $table->unique(['trip_day_id','attendant_id','active'], 'u_day_attendant_active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_day_attendants');
    }
};
