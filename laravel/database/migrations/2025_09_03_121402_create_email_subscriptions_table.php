<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->morphs('userable');
            $table->string('email')->index();
            $table->boolean('subscribed')->default(true)->index();
            $table->string('token', 64)->unique()->nullable(); // for signed/unsubscribe links
            $table->string('reason')->nullable();               // optional reason
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('preferences')->nullable();            // granular types (newsletters, alerts...)
            $table->timestamps();

            $table->unique(['email']); // one row per email
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_subscriptions');
    }
};
