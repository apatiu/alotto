<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'product_id',
        'product_percent_id',
        'product_type_id',
        'product_type_name',
        'product_design_id',
        'product_size_id',
        'product_name',
        'product_wt',
        'cost_wage',
        'tag_wage',
        'cost_price',
        'tag_price',
        'avg_cost_per_baht',
        'qty',
        'wt',
        'price_sale_total',
        'price_sale_gold',
        'price_sale_wage',
        'price_buy_total',
        'price_buy_calc',
        'sale_with_gold_price',
        'wage_by_pcs',
        'profit_wage',
        'profit_gold',
        'profit_total',
        'change_id',
        'change_price',
        'deposit',
        'discount',
        'real_cost_sale',
        'real_cost_buy',
        'real_profit_sale',
        'real_pro_buy'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
