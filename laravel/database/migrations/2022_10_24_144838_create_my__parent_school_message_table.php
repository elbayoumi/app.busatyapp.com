<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyParentSchoolMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my__parent_school_message', function (Blueprint $table) {
            $table->id();
            $table->foreignId('my__parent_id')->unsigned()->nullable()->references('id')->on('my__parents')->onDelete('cascade');
            $table->integer('status')->default(0);
            $table->foreignId('school_messages_id')->unsigned()->nullable()->references('id')->on('school_messages')->onDelete('cascade');
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
        Schema::dropIfExists('my__parent_school_message');
    }
}
