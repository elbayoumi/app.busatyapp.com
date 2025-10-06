<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripAttendantsTable extends Migration
{
    public function up()
    {
        Schema::create('trip_attendants', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('trip_id')
                ->constrained('trips')
                ->cascadeOnDelete();

            $table->foreignId('attendant_id')
                ->nullable()
                ->constrained('attendants')
                ->nullOnDelete();


            // Composite index for fast lookups
            $table->unique(['trip_id', 'attendant_id'], 'trip_attendant_unique');

            // If you need record timestamps (optional)
            // $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_attendants');
    }
}
