<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'team_id', 'shift_id', 'method_id',
        'emp_name', 'acc_date', 'payment_no', 'dt', 'detail', 'bill_id',
        'pay', 'receive', 'transfer_bank', 'transfer_acc_no', 'creditcard_bank',
        'creditcard_bank_no', 'creditcard_percent_fee', 'cancel_on', 'cancel_reason',
        'cancel_emp_name', 'payment_type_id', 'paymentable_id', 'paymentable_type',
        'user_id'
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            $code = 'PM' . substr('00' . request()->user()->currentTeam->id, -2);
            $code .= date('Ymd', strtotime($payment->attributes['dt'])) . '-';

            $latest = Payment::where('code', 'like', $code . '%')->select('code')->orderBy('code', 'desc')->first();

            if (!$latest)
                $code .= '0001';
            else
                $code .= substr('000' . (intval(substr($latest->attributes['code'], -4)) + 1), -4);

            $payment->attributes['code'] = $code;

            if (!$payment->team_id)
                $payment->team_id = Auth::user()->currentTeam->id;

            if (!$payment->shift_id)
                $payment->shift_id = Shift::current()->id;
        });
    }

    public function parse($data, $paymentTypeId = null)
    {
        $this->fill($data);
        $dt = isset($data['dt']) ? jsDateToDateTimeString($data['dt']) : now()->toDateTimeString();
        $this->dt = $dt;

        $this->user_id = $data['user_id'] ?? Auth::user()->id;

        $this->payment_type_id = $paymentTypeId;
        if (isset($data['payment_type_id'])) {
            if ($data['payment_type_id']) {
                $this->payment_type_id = $data['payment_type_id'];
            }
        }

        if ($this->payment_type->type === 'pay') {
            $this->pay = $data['amount'] < 0 ? abs($data['amount']) : null;
        } else {
            $this->receive = $data['amount'] >= 0 ? $data['amount'] : null;
        }
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function pawn_int_receives()
    {
        return $this->morphedByMany(PawnIntReceive::class, 'paymentable');
    }

    public function stockImport()
    {
        return $this->morphedByMany(StockImport::class, 'paymentable');
    }

    public function savingDetails()
    {
        return $this->morphedByMany(SaleDetail::class, 'paymentable');
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    static function summaryByDays($d_begin, $d_end)
    {

    }

    static function summaryByShift($shift_id)
    {

    }
}
