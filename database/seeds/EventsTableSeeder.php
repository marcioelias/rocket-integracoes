<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clearEvents();

        DB::table('events')->insert([
            'name' => 'Vencimento de Boleto',
            'system_event' => true
        ]);
    }

    public function clearEvents() {
        DB::table('events')->where('system_event', true)->delete();
    }
}
