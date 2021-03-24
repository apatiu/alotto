<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'cost_wage', 'tag_wage', 'cost_price', 'tag_price',
        'qty_begin', 'qty_in', 'qty_out', 'qty_end',
        'weight_begin', 'weight_in', 'weight_out', 'weight_end',
        'dt', 'ref_no', 'desctiption'];
    public $timestamps = false;
}
