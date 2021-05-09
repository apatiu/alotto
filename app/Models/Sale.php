<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'dt',
        'gold_price',
        'customer_id', 'customer_name', 'customer_phone', 'customer_tax_id',
        'total_price_sale',
        'total_price_buy',
        'total_amount',
        'total_wt_sale',
        'total_wt_buy',
        'total_qty_sale',
        'product_cost_price',
        'total_deposit',
        'user_id',
        'user_name',
        'team_id',
        'note',
        'type',
        'status',
        'user_id_cancel',
        'gold_price_buy',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            $code = 'IV' . substr('0' . request()->user()->currentTeam->id,-2);
            $code .= date('Ymd', strtotime($payment->attributes['dt']));

            $latest = Sale::where('code', 'like', $code . '%')->select('code')->orderBy('code', 'desc')->first();

            if (!$latest)
                $code .= '0001';
            else
                $code .= substr('0000' . (intval(substr($latest->attributes['code'], -4)) + 1), -4);

            $payment->attributes['code'] = $code;
        });
    }

    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function sales()
    {
        return $this->details()->whereStatus('sale');
    }

    public function buys()
    {
        return $this->details()->whereStatus('buy');
    }

    public function stock_card()
    {
        return $this->morphOne(StockCard::class, 'ref');
    }
}
