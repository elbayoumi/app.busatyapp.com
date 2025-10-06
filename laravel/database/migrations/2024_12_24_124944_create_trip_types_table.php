<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripTypesTable extends Migration
{
    public function up()
    {
        Schema::create('trip_types', function (Blueprint $table) {
            $table->id();
            $table->morphs('tripable'); // علاقة مع المدرسة أو كيانات أخرى في المستقبل
            $table->string('name'); // اسم نوع الرحلة
            $table->text('description')->nullable(); // وصف اختياري
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_types');
    }
}
