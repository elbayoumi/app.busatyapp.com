<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreatedByTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('created_by', function (Blueprint $table) {
            $table->id();
            $table->morphs('userable'); // For polymorphic relation (model_type and model_id)
            $table->morphs('creatable'); // For polymorphic relation (model_type and model_id)
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
        Schema::dropIfExists('created_by');
    }
}
