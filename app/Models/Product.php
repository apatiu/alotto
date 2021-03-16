<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function gen_id()
    {
        $gold_percent = $this->attributes['gold_percent'];
        $product_type_id = $this->attributes['product_type_id'];
        $product_weight = $this->weightbaht ? $this->weight * 15.2 : $this->weight;
        $product_design = $this->product_design_id;
        $product_size = $this->size;
    }
}
