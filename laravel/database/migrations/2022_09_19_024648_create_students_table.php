<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary()->comment('Primary key using UUID');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->foreignId('grade_id')->unsigned()->nullable()->references('id')->on('grades')->onDelete('cascade');
            $table->foreignId('gender_id')->unsigned()->nullable()->references('id')->on('genders')->onDelete('set null');
            $table->foreignId('school_id')->unsigned()->nullable()->references('id')->on('schools')->onDelete('cascade');
            // $table->foreignId('religion_id')->unsigned()->nullable()->references('id')->on('religions')->onDelete('set null');
            // $table->foreignId('type__blood_id')->unsigned()->nullable()->references('id')->on('type__bloods')->onDelete('set null');
            $table->foreignId('classroom_id')->unsigned()->nullable()->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreignId('bus_id')->unsigned()->nullable()->references('id')->on('buses')->onDelete('set null');
            $table->string('address')->nullable();
            // $table->string('city_name')->nullable();
            $table->integer('status')->default(1);
            $table->string('trip_type')->default('full_day'); //['full_day', 'end_day', 'start_day']
            $table->string('parent_key');
            $table->string('parent_secret');
            // $table->date('Date_Birth')->nullable();
            $table->string('logo')->default('default.png');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();

        });
    }


    public function down()
    {
        Schema::dropIfExists('students');
    }
}
