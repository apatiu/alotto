<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockImportLine extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','gold_percent','product_type_id',
        'product_design','product_size','product_name','product_weight',
        'product_min','cost_wage','tag_wage','cost_price','tag_price',
        'sale_with_gold_price','product_qty','product_weight_total',
        'avg_cost_per_baht','descriptions'];

    protected $appends= ['cost_wage_total','cost_price_total'];

    public function getCostWageTotalAttribute() {
        return $this->attributes['cost_wage'] * $this->attributes['product_qty'];
    }

    public function getCostPriceTotalAttribute() {
        return $this->attributes['cost_price'] * $this->attributes['product_qty'];
    }
}
