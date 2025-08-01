<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configurações de Gold
        Setting::set(
            'available_gold',
            '1000000000',
            'integer',
            'Gold Disponível',
            'Quantidade total de gold disponível para venda',
            'gold'
        );

        Setting::set(
            'gold_price_per_1000',
            '0.12',
            'string',
            'Preço por 1000 Gold',
            'Preço em reais por 1000 unidades de gold',
            'gold'
        );

        Setting::set(
            'gold_min_purchase',
            '1000',
            'integer',
            'Compra Mínima de Gold',
            'Quantidade mínima de gold que pode ser comprada',
            'gold'
        );

        Setting::set(
            'gold_max_purchase',
            '1000000',
            'integer',
            'Compra Máxima de Gold',
            'Quantidade máxima de gold que pode ser comprada por pedido',
            'gold'
        );

        // Configurações Gerais
        Setting::set(
            'site_name',
            'DK Pandora',
            'string',
            'Nome do Site',
            'Nome exibido no site',
            'general'
        );

        Setting::set(
            'site_description',
            'Sua loja confiável para itens e gold do Grand Fantasia Violet',
            'string',
            'Descrição do Site',
            'Descrição exibida no site',
            'general'
        );

        Setting::set(
            'delivery_time',
            '30',
            'integer',
            'Tempo de Entrega (minutos)',
            'Tempo estimado de entrega em minutos',
            'general'
        );

        Setting::set(
            'support_whatsapp',
            '+5511999999999',
            'string',
            'WhatsApp de Suporte',
            'Número do WhatsApp para suporte',
            'general'
        );
    }
}
