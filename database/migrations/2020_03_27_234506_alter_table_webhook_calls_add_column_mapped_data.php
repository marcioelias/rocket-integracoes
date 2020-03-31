<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableWebhookCallsAddColumnMappedData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webhook_calls', function (Blueprint $table) {
            $table->json('mapped_data')->nullable()->after('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webhook_calls', function (Blueprint $table) {
            $table->dropColumn('mapeed_data');
        });
    }
}
