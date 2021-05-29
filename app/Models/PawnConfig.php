<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PawnConfig extends Model
{
    use HasFactory;

    public function team() {
        return $this->belongsTo(Team::class);
    }
    public function intr_range_rates() {
        return $this->hasMany(IntrRangeRate::class);
    }

    public function intr_discount_rates() {
        return $this->hasMany(IntrDiscountRate::class);
    }
}
