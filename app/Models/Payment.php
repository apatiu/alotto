<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($payment) {
            $id = 'PM' . request()->user()->currentTeam->id . '-';
            $id .= date('Ymd', strtotime($payment->attributes['dt'])) . '-';

            $latest = Payment::where('id', 'like', $id . '%')->select('id')->orderBy('id', 'desc')->first();

            if ($latest === null)
                $id .= '001';
            else
                $id .= substr('00' . (intval(substr($latest->attributes['id'], -3)) + 1), -3);

            $payment->attributes['id'] = $id;
        });
    }


    protected $fillable = [
        'team_id', 'emp_name', 'acc_date', 'payment_no', 'dt', 'detail', 'bill_id',
        'pay', 'receive', 'method', 'transfer_bank', 'transfer_acc_no', 'creditcard_bank',
        'creditcard_bank_no', 'creditcard_percent_fee', 'cancel_on', 'cancel_reason',
        'cancel_emp_name', 'payment_type_id', 'paymentable_id', 'paymentable_type',
    ];


    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function payment_type() {
        return $this->belongsTo(PaymentType::class);
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function gen_id($dt = null)
    {
        $id = 'PM' . request()->user()->currentTeam->id . '-';
        $id .= date('Ymd', strtotime($dt)) . '-';

        $latest = Payment::where('id', 'like', $id . '%')->select('id')->orderBy('id', 'desc')->first();

        if ($latest === null)
            $id .= '001';
        else
            $id .= substr('00' . (intval(substr($latest->attributes['id'], -3)) + 1), -3);

        return $id;
    }
}
