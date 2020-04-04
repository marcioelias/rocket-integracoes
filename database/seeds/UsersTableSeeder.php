<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clearUsers();

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@app.com',
            'username' => 'admin',
            'password' => bcrypt('admin')
        ]);
    }

    public function clearUsers() {
        DB::table('users')->truncate();
    }
}
