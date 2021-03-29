<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PawnIntReceives extends Model
{
    use HasFactory;

    protected $fillable = ['pawn_id', 'dt', 'dt_end', 'amount'];
}
