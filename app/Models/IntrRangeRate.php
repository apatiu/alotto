<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntrRangeRate extends Model
{
    use HasFactory;

    protected $fillable = ['pawn_config_id', 'min', 'max', 'rate'];
}
