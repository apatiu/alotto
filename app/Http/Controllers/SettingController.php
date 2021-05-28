<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\IntrDiscountRate;
use App\Models\IntrRangeRate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Helpers\MetaHelper as Meta;

class SettingController extends Controller
{
    public function index()
    {

        return Inertia::render('Settings/Index', [
            'company' => [
                'name' => Meta::get('company_name', 'บริษัท กำไรดี จำกัด'),
                'logo_url' => Meta::get('company_logo_url'),
                'addr' => Meta::get('company_addr'),
                'tel'=> Meta::get('company_tel'),
                'tax_id'=> Meta::get('company_tax_id'),
            ],
            'company_config' => [
                'bullion_price_diff' => Meta::get('gold_price_diff','100'),
                'gold_baht_weight' => Meta::get('gold_baht_weight', '15.2'),
                'gold_buy_price_devide_by_gold_baht_weight' =>
                    Meta::get('gold_buy_price_devide_by_gold_baht_weight', 1) ? true : false
            ],
            'pawn_config' => [
                'pawn_life' => Meta::get('pawn_life',3),
                'int_default_rate' => Meta::get('pawn_int_default_rate',3),
                'int_min' => Meta::get('pawn_int_min',50),
                'int_range_rates' => IntrRangeRate::all() ?? [],
                'int_discount_rates' => IntrDiscountRate::all() ?? [],
                'int_range_rates_enable' => Meta::get('pawn_int_range_rates_enable', false),
                'int_discount_rates_enable' => Meta::get('pawn_int_discount_rates_enable', false),
            ],
            'bank_accounts' => BankAccount::all()
        ]);
    }
}
