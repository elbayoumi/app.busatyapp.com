<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripDaysTable extends Migration
{
    public function up()
    {
        Schema::create('trip_days', function (Blueprint $table) {
            $table->id();
            // Link to base trip template
            $table->foreignId('trip_id')
                ->constrained('trips')
                ->cascadeOnDelete();

            // Day info
            $table->date('date')->index();

            // Status: 0 = scheduled, 1 = open, 2 = closed
            $table->unsignedTinyInteger('status')->default(0)->index();

            // Bus snapshot (in case trip.bus_id changes later)
            $table->foreignId('bus_id')
                ->nullable()
                ->constrained('buses')
                ->nullOnDelete();

            // Lifecycle
            $table->nullableMorphs('openable'); // openable_id, openable_type

            $table->timestamp('opened_at')->nullable()->index();
            $table->timestamp('closed_at')->nullable()->index();


        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_days');
    }
}
