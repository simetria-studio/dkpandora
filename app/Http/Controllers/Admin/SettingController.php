<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getAllGrouped();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'required'
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }

    public function updateGold(Request $request)
    {
        $request->validate([
            'available_gold' => 'required|integer|min:0',
            'gold_price_per_1000' => 'required|numeric|min:0',
            'gold_min_purchase' => 'required|integer|min:1000',
            'gold_max_purchase' => 'required|integer|min:1000'
        ]);

        Setting::set('available_gold', $request->available_gold, 'integer', 'Gold Disponível', 'Quantidade total de gold disponível para venda', 'gold');
        Setting::set('gold_price_per_1000', $request->gold_price_per_1000, 'string', 'Preço por 1000 Gold', 'Preço em reais por 1000 unidades de gold', 'gold');
        Setting::set('gold_min_purchase', $request->gold_min_purchase, 'integer', 'Compra Mínima de Gold', 'Quantidade mínima de gold que pode ser comprada', 'gold');
        Setting::set('gold_max_purchase', $request->gold_max_purchase, 'integer', 'Compra Máxima de Gold', 'Quantidade máxima de gold que pode ser comprada por pedido', 'gold');

        return redirect()->back()
            ->with('success', 'Configurações de Gold atualizadas com sucesso!');
    }
}
