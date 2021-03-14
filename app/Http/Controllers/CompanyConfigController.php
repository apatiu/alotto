<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Meta;

class CompanyConfigController extends Controller
{
    public function store(Request $request)
    {
        Meta::set('bullion_price_diff', $request->input('bullion_price_diff'));
        Meta::set('gold_baht_weight', $request->input('gold_baht_weight'));
        Meta::set('gold_buy_price_devide_by_gold_baht_weight', $request->input('gold_buy_price_devide_by_gold_baht_weight'));
        return redirect()->back();
    }
}
