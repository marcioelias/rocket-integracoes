<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableActionsAddColumnName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->string('name')->after('id')->nullable();
        });

        try {
            DB::beginTransaction();
            $this->updateActionNames();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->down();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    private function updateActionNames() {
        $actions = \App\Action::with('event', 'product')->get();

        foreach ($actions as $action) {
            $action->name = $action->product->name.' -> '.$action->event->name;
            $action->save();
        }
    }
}
