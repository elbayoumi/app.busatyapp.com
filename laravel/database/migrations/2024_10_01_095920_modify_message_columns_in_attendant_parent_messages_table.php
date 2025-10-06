<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMessageColumnsInAttendantParentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendant_parent_messages', function (Blueprint $table) {
            // أولاً: حذف الأعمدة القديمة
            $table->dropColumn(['message', 'message_en', 'title']);
        });

        Schema::table('attendant_parent_messages', function (Blueprint $table) {
            // ثانياً: إضافة الأعمدة من جديد مع التعديلات المطلوبة
            $table->text('message');
            $table->text('message_en');
            $table->string('title', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendant_parent_messages', function (Blueprint $table) {

        });
    }
}
