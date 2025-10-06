<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->unsigned()->nullable()->references('id')->on('schools')->onDelete('cascade');
            $table->foreignId('bus_id')->unsigned()->nullable()->references('id')->on('buses')->onDelete('cascade');
            $table->foreignId('my__parent_id')->unsigned()->nullable()->references('id')->on('my__parents')->onDelete('cascade');
            $table->uuid('student_id'); // UUID type for student_id, matching the 'id' in 'students' table
            $table->foreign('student_id')
            ->references('id')
            ->on('students')
            ->onDelete('cascade');
                  $table->date('attendence_date');
            $table->string('attendence_type')->nullable(); //['full_day', 'end_day', 'start_day']
            $table->tinyText('created_by')->nullable();
            $table->tinyText('updated_by')->nullable();

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
        Schema::dropIfExists('absences');
    }
}
