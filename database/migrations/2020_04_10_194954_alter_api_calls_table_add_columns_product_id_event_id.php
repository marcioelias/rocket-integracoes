<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApiCallsTableAddColumnsProductIdEventId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_calls', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('api_endpoint_id');
            $table->unsignedBigInteger('product_id')->nullable()->after('event_id');
            $table->string('transaction_code')->after('product_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_calls', function (Blueprint $table) {
            $table->dropForeign('api_calls_event_id_foreign');
            $table->dropForeign('api_calls_product_id_foreign');
            $table->dropColumn('event_id');
            $table->dropColumn('product_id');
            $table->dropColumn('transaction_code');
        });
    }
}
