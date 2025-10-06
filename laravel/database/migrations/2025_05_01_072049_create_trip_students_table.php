<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('trip_students');

        Schema::create('trip_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->uuid('student_id')->nullable();
            $table->foreign("trip_id")->references("id")->on("trips")->onDelete('cascade');
            $table->foreign("student_id")->references("id")->on("students")->onDelete('set null');
            $table->unique(['trip_id', 'student_id'], 'trip_student_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_students');
    }
}
