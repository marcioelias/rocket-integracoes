<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBilletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->string('billet_number');
            $table->string('url');
            $table->unsignedDouble('amount', 19, 2);
            $table->dateTime('expiration_date');
            $table->unsignedBigInteger('webhook_call_id');
            $table->enum('billet_status', ['pending', 'paid'])->default('pending');
            $table->foreign('webhook_call_id')->references('id')->on('webhook_calls');
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
        Schema::dropIfExists('billets');
    }
}
