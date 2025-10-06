<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendants', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable()->unique(); // or after any column you prefer

            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->foreignId('gender_id')->unsigned()->nullable()->references('id')->on('genders')->onDelete('cascade');
            $table->foreignId('school_id')->unsigned()->nullable()->references('id')->on('schools')->onDelete('cascade');
            $table->foreignId('religion_id')->unsigned()->nullable()->references('id')->on('religions')->onDelete('cascade');
            $table->foreignId('type__blood_id')->unsigned()->nullable()->references('id')->on('type__bloods')->onDelete('cascade');
            $table->foreignId('bus_id')->unsigned()->nullable()->references('id')->on('buses')->onDelete('set null');
            $table->date('Joining_Date')->nullable();
            $table->string('address')->nullable();
            $table->string('city_name')->nullable();
            $table->integer('status')->default(0);
            $table->string('logo')->default('default.png');
            $table->enum('type', ['drivers', 'admins'])->default('admins');
            $table->string('phone');
            $table->date('birth_date')->nullable();
            $table->string('email_verified_at')->default(1);
            $table->string('firebase_token')->nullable();
            $table->string('typeAuth')->default('attendants');
            $table->rememberToken();
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
        Schema::dropIfExists('attendants');
    }
}
