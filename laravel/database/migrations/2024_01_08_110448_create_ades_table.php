<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ades', function (Blueprint $table) {
            $table->id();

            $table->string("title")->nullable();
            $table->string("body")->nullable();
            $table->string("link")->nullable();
            $table->string("image")->nullable();
            $table->string("alt")->nullable();
            
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
        Schema::dropIfExists('ades');
    }
}
