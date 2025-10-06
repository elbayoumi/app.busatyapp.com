<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCustomIdentifiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_identifiers', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique()->default(DB::raw('(UUID())')); // Auto-generate UUID
            $table->string('identifiable_id')->nullable(); // Change this from integer to string
            $table->string('identifiable_type')->nullable(); // Polymorphic relation
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
        Schema::dropIfExists('custom_identifiers');
    }
}
