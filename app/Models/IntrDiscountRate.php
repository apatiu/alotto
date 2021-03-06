<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntrDiscountRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'pawn_config_id', 'days', 'rate'];
}
