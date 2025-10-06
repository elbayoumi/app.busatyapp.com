<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            // This adds notificationable_id and notificationable_type for polymorphic relation
            $table->morphs('userable');
            // This correctly creates the foreign key referencing the notifications table
            $table->foreignId('notification_id')
                  ->constrained('notifications')
                  ->onDelete('cascade'); // Related records will be deleted when the notification is removed

            // Adding a column to track read status (optional)
            $table->boolean('read_at')->default(0); // Allows tracking of when the notification was read
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the user_notifications table if it exists
        Schema::dropIfExists('user_notifications');
    }
}
