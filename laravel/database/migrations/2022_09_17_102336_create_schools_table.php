<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('city_name')->nullable();
            $table->integer('status')->default(1);
            $table->string('logo')->default('default.png');
            $table->rememberToken();
            $table->timestamps();
            $table->string('typeAuth')->default('school');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->unsignedTinyInteger('trip_start_end_notification_status')->default(1);
            $table->unsignedTinyInteger('student_absence_notification_status')->default(1);
            $table->unsignedTinyInteger('student_address_notification_status')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
