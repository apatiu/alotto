<?php

namespace App\Http\Controllers;

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
                'gold_bath_weight' => Meta::get('gold_bath_weight', '15.2'),
                'gold_buy_price_devide_by_gold_bath_weight' =>
                    Meta::get('gold_buy_price_devide_by_gold_bath_weight', 1) ? true : false
            ]
        ]);
    }
}
