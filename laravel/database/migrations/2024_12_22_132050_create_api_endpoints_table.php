<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiEndpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_endpoints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id'); // ارتباط بالكوليكشن
            $table->string('name'); // اسم الـ API
            $table->string('method'); // GET, POST, PUT, DELETE
            $table->string('url'); // رابط الـ API
            $table->json('headers')->nullable(); // أي هيدرز إضافية
            $table->json('body')->nullable(); // بيانات الـ body
            $table->json('auth')->nullable(); // بيانات التوثيق
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_endpoints');
    }
}
