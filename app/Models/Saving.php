<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Saving extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_id',
        'team_id',
        'gold_price_sale',
        'dt',
        'dt_due',
        'dt_close',
        'price_total',
        'price_pay',
        'price_remain',
        'note',
        'items_wt',
        'items_qty',
        'status',
        'user_id',
        'life',
        'price_refund',
        'price_forward',
        'prev_id',
        'next_id',
        'saving_wt'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (is_null($model->code)) {
                $model->code = 'S' . substr('0000' . ($model->id + 1), -5);
            }

            if (!$model->user_id) {
                $model->user_id = Auth::user()->id;
            }
        });
    }

    public function getWtAttribute()
    {
        return $this->items()->sum('weight');
    }

    public function getPrevCodeAttribute()
    {
        if (!empty($this->attributes['prev_id']))
            return 'S' . substr('0000' . $this->attributes['prev_id'], -5);
    }

    public function getNextCodeAttribute()
    {
        if (!empty($this->attributes['next_id']))
            return 'S' . substr('0000' . $this->attributes['next_id'], -5);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function items()
    {
        return $this->hasMany(SavingItem::class);
    }

    public function details()
    {
        return $this->hasMany(SavingDetail::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateTotal() {
        $this->price_pay = $this->loadSum('details','amount')->details_sum_amount;
        $this->price_remain = $this->price_total-$this->price_pay;
    }
}
