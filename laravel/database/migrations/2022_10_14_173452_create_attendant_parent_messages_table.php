<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendantParentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendant_parent_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendant_id')->unsigned()->nullable()->references('id')->on('attendants')->onDelete('cascade');
            // $table->foreignId('my__parent_id')->unsigned()->nullable()->references('id')->on('my__parents')->onDelete('cascade');
            $table->uuid('student_id'); // UUID type for student_id, matching the 'id' in 'students' table
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->integer('status')->default(0);
            $table->string('message');
            $table->string('message_en')->nullable(); // Adjust 'message' to the correct column name to place 'message_en' after it.
            $table->string('title');
            $table->string('notifications_type')->nullable();
            $table->enum('trip_type',['end_day','start_day']);
            $table->foreignId('trip_id')->unsigned()->nullable()->references('id')->on('trips')->onDelete('cascade');

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
        Schema::dropIfExists('attendant_parent_messages');
    }
}
