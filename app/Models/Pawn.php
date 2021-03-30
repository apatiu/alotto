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
        return $this->hasMany(PawnIntReceives::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function setDtAttribute($value)
    {
        $this->attributes['dt'] = Carbon::create($value)
            ->timezone('Asia/Bangkok')
            ->toDateTimeString();
    }

    public function setDtEndAttribute($value)
    {
        $this->attributes['dt_end'] = Carbon::create($value)
            ->timezone('Asia/Bangkok')
            ->toDateTimeString();
    }
}
