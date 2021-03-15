<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDesign extends Model
{
    use HasFactory;

    protected $fillable = ['product_type_id', 'name'];

    public function product_type()
    {
        return $this->belongsTo(ProductType::class);
    }
}
