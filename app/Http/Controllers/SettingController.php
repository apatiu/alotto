<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Meta;

class SettingController extends Controller
{
    public function index() {
        return Inertia::render('Settings/Index',[
            'company'=>[
                'name' => Meta::get('company_name'),
                'logo_url' => Meta::get('company_logo_url')
            ]
        ]);
    }
}
