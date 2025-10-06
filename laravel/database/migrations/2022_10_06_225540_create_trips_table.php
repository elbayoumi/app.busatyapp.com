<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            // Tenant scope
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->cascadeOnDelete();
            $table->string('name')->nullable();
            // Bus assigned to this trip (can be null if bus deleted)
            $table->foreignId('bus_id')
                ->nullable()
                ->constrained('buses')
                ->nullOnDelete();

            // Core trip info
            $table->string('trip_type')->nullable();//['start_day', 'end_day']

            // Status: 0 = draft, 1 = open, 2 = closed (example)
            $table->unsignedTinyInteger('status')->default(0)->index();

            // Location (decimal is better for lat/long)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Lifecycl
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
