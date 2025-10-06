<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBussIdToAttendantParentMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('attendant_parent_messages', function (Blueprint $table) {
            $table->foreignId('bus_id')->unsigned()->nullable()->references('id')->on('buses')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('attendant_parent_messages', function (Blueprint $table) {
            $table->dropForeign(['bus_id']);
            $table->dropColumn('bus_id');
        });
    }
}
