<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SubscriptionStatus;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name')->default('dashboard');
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('status', SubscriptionStatus::values())->default(SubscriptionStatus::NotSubscribed->value); // Enum for subscription status
            $table->string('payment_method')->default('dashboard'); // e.g., credit_card, paypal
            $table->string('transaction_id')->nullable()->unique(); // Unique identifier for the transaction

            // Define foreign key relationship
            $table->nullableMorphs('subscriptable');
                        $table->softDeletes();

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
        Schema::dropIfExists('subscriptions');
    }
}
