<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->unsigned()->nullable()->references('id')->on('topics')->onDelete('cascade');
            $table->morphs('userable'); // For polymorphic relation (model_type and model_id)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_users');
    }
}
