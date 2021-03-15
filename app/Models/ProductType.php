<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = ['id', 'name'];
    public $incrementing = false;
    use HasFactory;

    public function getIdAttribute($value)
    {
        return strtoupper($value);
    }

    public function setIdAttribute($value)
    {
        $this->attributes['id'] = strtoupper($value);
    }

    public function designs()
    {
        return $this->hasMany(ProductDesign::class);
    }
}
