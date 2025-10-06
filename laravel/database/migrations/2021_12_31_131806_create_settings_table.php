<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
			$table->string('slogan')->nullable();
            $table->string('short_description')->nullable();

            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();


            $table->string('light_logo')->nullable();
			$table->string('dark_logo')->nullable();
            $table->string('favicon')->nullable();
			$table->string('dashboard_logo')->nullable();

            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();

			$table->string('linkedin')->nullable();
            $table->string('behance')->nullable();
			$table->string('github')->nullable();
			$table->string('twitter')->nullable();
			$table->string('facebook')->nullable();
			$table->string('instagram')->nullable();


            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_image')->nullable();


            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('smtp_encryption')->nullable()->default('ssl');
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();

            $table->string('smtp_from_address')->nullable();
            $table->string('smtp_from_name')->nullable();


			$table->string('merchants_latest_version')->default('1.0.0');
			$table->string('distributors_latest_version')->default('1.0.0');


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
        Schema::dropIfExists('settings');
    }
}
