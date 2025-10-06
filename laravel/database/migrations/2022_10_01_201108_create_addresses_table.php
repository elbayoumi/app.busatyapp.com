<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->unsigned()->nullable()->references('id')->on('schools')->onDelete('cascade');
            $table->foreignId('bus_id')->unsigned()->nullable()->references('id')->on('buses')->onDelete('set null');
            $table->integer('status')->default(0);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignId('my__parent_id')->unsigned()->nullable()->references('id')->on('my__parents')->onDelete('cascade');
            $table->uuid('student_id'); // UUID type for student_id, matching the 'id' in 'students' table
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->string('old_address')->nullable();
            $table->string('old_latitude')->nullable();
            $table->string('old_longitude')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
