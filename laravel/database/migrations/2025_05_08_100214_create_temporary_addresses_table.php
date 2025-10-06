<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_addresses', function (Blueprint $table) {
            $table->id();
            $table->uuid('student_id'); // UUID type for student_id, matching the 'id' in 'students' table
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->date('from_date');
            $table->date('to_date');
            $table->string('accept_status')->default(0); 
            // [ 0 => 'pending', 1 => 'approved', 2 => 'rejected' , 3 => 'canceled' , 4 => 'replaced' ]
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
        Schema::dropIfExists('temporary_addresses');
    }
}
