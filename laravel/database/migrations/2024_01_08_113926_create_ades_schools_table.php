<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdesSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ades_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->unsigned()->nullable()->references('id')->on('schools')->onDelete('cascade');
            $table->foreignId('ades_id')->unsigned()->nullable()->references('id')->on('ades')->onDelete('cascade');

            // School Information

            $table->enum("to",['all','school','parent','attendance'])->default('all');

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
        Schema::dropIfExists('ades_schools');
    }
}
