<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldPercent extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id',
        'gold_percent',
        'percent_sale', 'add_sale', 'percent_buy', 'deduct_buy',
        'percent_deduct_total_buy', 'percent_buy_cost', 'deduct_buy_cost', 'deduct_buy_gold'];
}
