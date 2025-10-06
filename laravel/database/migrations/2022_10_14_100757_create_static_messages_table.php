<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_messages', function (Blueprint $table) {
            $table->id();
            $table->longText('message');
            $table->enum('trip_type', ['end_day', 'start_day'])->nullable();
            $table->foreignId('school_id')->unsigned()->nullable()->references('id')->on('schools')->onDelete('cascade');
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
        Schema::dropIfExists('static_messages');
    }
}
