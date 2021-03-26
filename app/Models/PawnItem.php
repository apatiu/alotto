<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PawnItem extends Model
{
    use HasFactory;

    public function pawn() {
        return $this->belongsTo(Pawn::class);
    }
}
