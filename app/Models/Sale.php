<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'dt',
        'gold_price',
        'customer_id', 'customer_name', 'customer_phone', 'customer_tax_id',
        'total_price_sale',
        'total_price_buy',
        'total_amount',
        'total_wt_sale',
        'total_wt_buy',
        'total_qty_sale',
        'product_cost_price',
        'total_deposit',
        'user_id',
        'user_name',
        'team_id',
        'note',
        'type',
        'status',
        'user_id_cancel',
        'gold_price_buy',
    ];

    public function details() {
        return $this->hasMany(SaleDetail::class);
    }

    public function sales(){
        return $this->details()->whereStatus('sale');
    }

    public function buys(){
        return $this->details()->whereStatus('buy');
    }
}
