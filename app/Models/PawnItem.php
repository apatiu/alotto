<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PawnItem extends Model
{
    use HasFactory;

    protected $fillable = ['pawn_id', 'gold_percent', 'product_type', 'weight', 'price'];

    public function pawn()
    {
        return $this->belongsTo(Pawn::class);
    }
}
