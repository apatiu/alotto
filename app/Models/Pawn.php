<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pawn extends Model
{
    use HasFactory;

    protected $fillable = ['dt', 'dt_end', 'customer_id', 'price', 'int_rate', 'life', 'status'];
    protected $appends = ['code', 'prev_code', 'next_code', 'weight'];

    public function oldGoldStockCard()
    {
        return $this->morphOne(OldGoldStockCard::class, 'ref');
    }

    public function getWeightAttribute()
    {
        return $this->items()->sum('weight');
    }

    public function getCodeAttribute()
    {
        return substr('0000' . $this->attributes['id'], -5);
    }

    public function getPrevCodeAttribute()
    {
        if (!empty($this->attributes['prev_id']))
            return substr('0000' . $this->attributes['prev_id'], -5);
    }

    public function getNextCodeAttribute()
    {
        if (!empty($this->attributes['next_id']))
            return substr('0000' . $this->attributes['next_id'], -5);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

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
