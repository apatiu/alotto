<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pawn extends Model
{
    use HasFactory;

    protected $fillable = ['dt', 'dt_end', 'customer_id', 'price', 'int_rate', 'life', 'status'];

    public function items()
    {
        return $this->hasMany(PawnItem::class);
    }

    public function int_receives()
    {
        return $this->hasMany(PawnIntReceive::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
