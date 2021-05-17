<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'saving_id',
        'amount',
        'dt',
        'note',
        'wt',
        'gold_price_sale',
        'is_forward'
    ];

    public function savingModel() {
        return $this->belongsTo(Saving::class,'saving_id','id');
    }
    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }
}
