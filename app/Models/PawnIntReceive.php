<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PawnIntReceive extends Model
{
    use HasFactory;

    protected $fillable = ['pawn_id', 'dt', 'dt_end', 'amount', 'month_pay'];


    public function pawn()
    {
        return $this->belongsTo(Pawn::class);
    }

    public function payments() {
        return $this->morphToMany(Payment::class, 'paymentable');
    }
}
