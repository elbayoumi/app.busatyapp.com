<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->unsigned()->nullable()->references('id')->on('schools')->onDelete('cascade');
            $table->enum('type',['start_day','end_day'])->default('start_day');
            $table->time('time_start')->default('6:00:00');
            $table->time('time_end')->default('10:00:00');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_trips');
    }
}
