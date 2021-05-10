<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    use HasFactory;

    protected $fillable = ['dt', 'gold_price_sale', 'gold_price_buy', 'gold_price_diff', 'gold_price_tax'];
}
