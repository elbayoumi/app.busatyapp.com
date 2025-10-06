<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_texts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->json('title'); // Notification title in JSON format for multi-language support
            $table->json('body'); // Notification body/content in JSON format for multi-language support
            $table->json('default_body'); // Notification body/content in JSON format for multi-language support
            $table->json('type')->nullable(); // Notification type (optional), stored in JSON format
            $table->string('for_model_type'); // The model type this notification is for (e.g., trips)
            $table->string('to_model_type'); // The model type this notification is intended for (e.g., parents)
            $table->json('model_additional'); // The model type this notification is intended for (e.g., parents)
            $table->boolean('group')->default(0); // Indicates if the notification is grouped (default is 0)
            $table->timestamps(); // Timestamps for creation and update
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_texts'); // Drop the table if it exists
    }
}
