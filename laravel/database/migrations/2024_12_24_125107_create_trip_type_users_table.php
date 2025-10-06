<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripTypeUsersTable extends Migration
{
    public function up()
    {
        Schema::create('trip_type_users', function (Blueprint $table) {
            $table->id();
            $table->morphs('userable'); // علاقة مع المستخدم أو كيان آخر
            $table->foreignId('trip_type_id')->constrained('trip_types')->onDelete('cascade'); // ربط مع أنواع الرحلات
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_type_users');
    }
}
