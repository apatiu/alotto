<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pawn extends Model
{
    use HasFactory;

    public function items() {
        return $this->hasMany(PawnItem::class);
    }

    public function payments() {
        return $this->morphMany(Payment::class,'paymentable');
    }
}
