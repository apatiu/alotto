<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    use HasFactory;

    protected $fillable = ['dt','ask','bid','diff'];
    protected $casts = [
        'ask' => 'decimal:2',
        'bid' => 'decimal:2'
    ];
}
