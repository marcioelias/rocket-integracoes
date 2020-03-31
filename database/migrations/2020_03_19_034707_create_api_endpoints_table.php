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
            $table->unsignedBigInteger('api_id');
            $table->string('name');
            $table->string('relative_url');
            $table->enum('method', ['GET', 'POST', 'PATCH', 'PUT', 'DELETE']);
            $table->enum('body', ['form-data', 'raw'])->default('raw');
            $table->string('data_field')->default('data');
            $table->json('json');
            $table->foreign('api_id')->references('id')->on('apis');
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
        Schema::dropIfExists('api_endpoints');
    }
}
