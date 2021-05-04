<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoldPercent;
use App\Models\ProductPercent;
use Illuminate\Http\Request;

class ProductPercentController extends Controller
{
    public function show($percent) {
        return GoldPercent::find($percent);
    }
}
