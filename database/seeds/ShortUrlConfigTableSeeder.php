<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShortUrlConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('short_url_configs')->get()->isEmpty()) {
            DB::table('short_url_configs')->insert([
                'id' => 1,
                'short_domain' => 'example.com',
                'short_url_api' => 'local',
                'api_key' => null
            ]);
        }
    }
}
