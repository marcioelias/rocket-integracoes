<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('api_endpoint_id');
            $table->integer('delay')->default(0);
            $table->text('message');
            $table->boolean('active')->default(true);
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('api_endpoint_id')->references('id')->on('api_endpoints');
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
        Schema::dropIfExists('actions');
    }
}
