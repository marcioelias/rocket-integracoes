<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clearSystemFields();

        DB::table('fields')->insert([
            [
                'label' => 'Campo de Mensagem',
                'field_name' => 'message',
                'system_field' => true
            ],
            [
                'label' => 'Webhook',
                'field_name' => 'webhook_name',
                'system_field' => true
            ],
            [
                'label' => 'Webhook URL',
                'field_name' => 'webhook_url',
                'system_field' => true
            ],
            [
                'label' => 'Webhook Token',
                'field_name' => 'webhook_token',
                'system_field' => true
            ],
            [
                'label' => 'API',
                'field_name' => 'api_name',
                'system_field' => true
            ],
            [
                'label' => 'API Base URL',
                'field_name' => 'api_base_url',
                'system_field' => true
            ],
            [
                'label' => 'API Token',
                'field_name' => 'api_token',
                'system_field' => true
            ],
            [
                'label' => 'API Usuário',
                'field_name' => 'api_username',
                'system_field' => true
            ],
            [
                'label' => 'API Senha',
                'field_name' => 'api_password',
                'system_field' => true
            ]
        ]);
    }

    public function clearSystemFields() {
        DB::table('fields')->where('system_field', true)->delete();
    }
}
