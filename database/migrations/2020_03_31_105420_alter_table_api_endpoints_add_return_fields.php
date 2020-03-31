<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableApiEndpointsAddReturnFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_endpoints', function (Blueprint $table) {
            $table->string('field_ok')->after('json');
            $table->string('code_ok')->after('field_ok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_endpoints', function (Blueprint $table) {
            $table->dropColumn('field_ok');
            $table->dropColumn('code_ok');
        });
    }
}
