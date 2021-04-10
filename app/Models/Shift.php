<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'd', 'cash',
        'cash_begin', 'cash_to_safe',
        'cash_to_bank',
        'cash_end',
        'bank',
        'card',
        'open_user_id',
        'close_user_id',
        'opened_at',
        'status'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'acc_date', 'd');
    }

    public function calc()
    {
        foreach (PaymentMethod::all() as $m) {
            $this->attributes[$m->id] = 0;
        }
        foreach ($this->payments as $payment) {
            $attr = '';
            if ($payment->receive > 0) {
                dd($payment->receive);
                $this->attributes[$payment->method] += $payment->receive;
            } else {
                $this->attributes[$payment->method] -= $payment->pay;
            }
        }
    }

    static function current() {
        return Shift::whereStatus('open')->first();
    }
}
