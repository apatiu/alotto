<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\HasTeams;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'dt',
        'gold_price_sale',
        'gold_price_buy',
        'gold_price_tax',
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
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            $code = 'IV' . substr('0' . request()->user()->currentTeam->id, -2);
            $code .= date('Ymd', strtotime($payment->attributes['dt']));

            $latest = Sale::where('code', 'like', $code . '%')->select('code')->orderBy('code', 'desc')->first();

            if (!$latest)
                $code .= '0001';
            else
                $code .= substr('0000' . (intval(substr($latest->attributes['code'], -4)) + 1), -4);

            $payment->attributes['code'] = $code;

            if (!$payment->gold_price_sale || !$payment->gold_price_buy || !$payment->gold_price_tax) {
                $gp = GoldPrice::now();
                $payment->gold_price_sale = $gp['gold_price_sale'];
                $payment->gold_price_buy = $gp['gold_price_buy'];
                $payment->gold_price_tax = $gp['gold_price_tax'];
            }
        });
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
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

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savings()
    {
        return $this->morphedByMany(Saving::class, 'saleable');
    }

    public function recalcTotal()
    {
        $this->total_price_sale = $this->loadSum('details', 'price_sale_total')->details_sum_price_sale_total;
        $this->total_price_buy = $this->loadSum('details', 'price_buy_total')->details_sum_price_buy_total;
        $this->total_amount = $this->total_price_sale - $this->total_price_buy;

        $this->total_wt_sale = $this->loadSum('sales', 'wt')->sales_sum_wt;
        $this->total_wt_buy = $this->loadSum('buys', 'wt')->buys_sum_wt;
        $this->total_qty_sale = $this->loadSum('sales', 'qty')->sales_sum_qty;
        $this->save();
    }

    public function createPayments($payments = null)
    {
        if (!$payments) {
            $payments = [[
                'team_id' => $this->team_id,
                'user_id' => $this->user_id,
                'shift_id' => Shift::current()->id,
                'dt' => $this->dt,
                'pay' => $this->total_price_buy,
                'receive' => $this->total_price_sale,
                'method_id' => 'cash',
                'payment_type_id' => $this->type
            ]];
        }
        $this->payments()->createMany($payments);

    }

    public function refresh()
    {

        $this->loadSum('buys','wt');
        $this->loadSum('sales','wt');
        $this->total_wt_buy = $this->buys_sum_wt;
        $this->total_wt_sale = $this->sales_sum_wt;
        $this->save();

        return parent::refresh(); // TODO: Change the autogenerated stub

    }



}
