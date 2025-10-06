<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->unsigned()->nullable()->references('id')->on('staff')->onDelete('cascade');
            $table->string('code');
            $table->string('model');
            $table->decimal('discount', 8, 2)->default(100.00);
            $table->unsignedInteger('user_limit')->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->dateTime('allow_at')->nullable();
            $table->dateTime('expires_at')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
