<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'team_id', 'shift_id', 'emp_name', 'acc_date', 'payment_no', 'dt', 'detail', 'bill_id',
        'pay', 'receive', 'method', 'transfer_bank', 'transfer_acc_no', 'creditcard_bank',
        'creditcard_bank_no', 'creditcard_percent_fee', 'cancel_on', 'cancel_reason',
        'cancel_emp_name', 'payment_type_id', 'paymentable_id', 'paymentable_type',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            $code = 'PM' . request()->user()->currentTeam->id . '-';
            $code .= date('Ymd', strtotime($payment->attributes['dt'])) . '-';

            $latest = Payment::where('code', 'like', $code . '%')->select('code')->orderBy('code', 'desc')->first();

            if (!$latest)
                $code .= '001';
            else
                $code .= substr('00' . (intval(substr($latest->attributes['code'], -3)) + 1), -3);

            $payment->attributes['code'] = $code;
        });
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function pawn_int_receives()
    {
        return $this->morphedByMany(PawnIntReceive::class, 'paymentable');
    }

    public function stockImport() {
        return $this->morphedByMany(StockImport::class,'paymentable');
    }

    public function paymentable()
    {
        return $this->morphTo();
    }
}
