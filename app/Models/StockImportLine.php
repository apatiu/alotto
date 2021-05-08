<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockImportLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code','gold_percent_id','product_type_id',
        'product_design_id','product_size_id','product_name',
        'product_weight','product_weightbaht',
        'product_min','cost_wage','tag_wage','cost_price','tag_price',
        'sale_with_gold_price','qty','product_weight_total',
        'avg_cost_per_baht','descriptions'];

    protected $appends= ['cost_wage_total','cost_price_total'];

    public function getCostWageTotalAttribute() {
        return $this->attributes['cost_wage'] * $this->attributes['qty'];
    }

    public function getCostPriceTotalAttribute() {
        return $this->attributes['cost_price'] * $this->attributes['qty'];
    }

    public function stock_import() {
        return $this->belongsTo(StockImport::class);
    }
}
