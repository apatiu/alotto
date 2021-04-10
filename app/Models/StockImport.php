<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'dt', 'team_id', 'supplier_id', 'emp_name', 'status',
        'stock_updated_on', 'note',
        'product_weight_total', 'tag_wage_total', 'tag_price_total',
        'product_qty_total', 'cost_wage_total', 'cost_price_total', 'cost_gold_total', 'real_weight_total', 'real_cost'];

    public function team() {
        return $this->belongsTo(Team::class);
    }
    public function lines() {
        return $this->hasMany(StockImportLine::class,'stock_import_id','id');
    }

    public function stockCards() {
        return $this->morphMany(StockCard::class,'ref');
    }

    public function payments() {
        return $this->morphToMany(Payment::class, 'paymentable');
    }
}
