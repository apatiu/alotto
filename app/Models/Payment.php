<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id', 'emp_name', 'acc_date', 'payment_no', 'dt', 'detail', 'bill_id',
        'pay', 'receive', 'method', 'transfer_bank', 'transfer_acc_no', 'creditcard_bank',
        'creditcard_bank_no', 'creditcard_percent_fee', 'cancel_on', 'cancel_reason',
        'cancel_emp_name', 'payment_type_id', 'paymentable_id', 'paymentable_type',
    ];

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
