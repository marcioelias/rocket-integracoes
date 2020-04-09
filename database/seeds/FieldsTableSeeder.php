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
                'label' => 'Webhook Código Transação',
                'field_name' => 'webhook_transaction_code',
                'system_field' => false,
                'can_delete' => false
            ],
            [
                'label' => 'Boleto - Linha Digitável',
                'field_name' => 'billet_number',
                'system_field' => false,
                'can_delete' => false
            ],
            [
                'label' => 'Boleto - URL',
                'field_name' => 'billet_url',
                'system_field' => false,
                'can_delete' => false
            ],
            [
                'label' => 'Boleto - Vencimento',
                'field_name' => 'billet_expiration_date',
                'system_field' => false,
                'can_delete' => false
            ],
            [
                'label' => 'Valor da Venda',
                'field_name' => 'sale_amount',
                'system_field' => false,
                'can_delete' => false
            ],
            [
                'label' => 'Data de Aprovação/Pagamento',
                'field_name' => 'date_approved',
                'system_field' => false,
                'can_delete' => false
            ],
            [
                'label' => 'Webhook',
                'field_name' => 'webhook_name',
                'system_field' => true,
                'can_delete' => false
            ],
            [
                'label' => 'Webhook URL',
                'field_name' => 'webhook_url',
                'system_field' => true,
                'can_delete' => false
            ],
            [
                'label' => 'Webhook Token',
                'field_name' => 'webhook_token',
                'system_field' => true,
                'can_delete' => false
            ],
            [
                'label' => 'API',
                'field_name' => 'api_name',
                'system_field' => true,
                'can_delete' => false
            ],
            [
                'label' => 'API Base URL',
                'field_name' => 'api_base_url',
                'system_field' => true,
                'can_delete' => false
            ],
            [
                'label' => 'API Token',
                'field_name' => 'api_token',
                'system_field' => true,
                'can_delete' => false
            ],
            [
                'label' => 'API Usuário',
                'field_name' => 'api_username',
                'system_field' => true,
                'can_delete' => false
            ],
            [
                'label' => 'API Senha',
                'field_name' => 'api_password',
                'system_field' => true,
                'can_delete' => false
            ]
        ]);
    }

    public function clearSystemFields() {
        DB::table('fields')->where('system_field', true)->delete();
    }
}
