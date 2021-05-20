<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'cash_begin', 'cash_in', 'cash_out', 'cash_to_safe', 'cash_to_bank', 'cash_end',
        'bank',
        'card',
        'open_user_id',
        'close_user_id',
        'opened_at',
        'closed_at',
        'status'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function calc()
    {
        $rows = DB::table('payments')
            ->select('method_id', DB::raw('SUM(receive) as receive, SUM(pay) as pay'))
            ->groupBy('method_id')
            ->where('shift_id', '=', $this->id)
            ->get();
        foreach ($rows as $row) {
            if ($row->method_id === 'cash') {
                $this->cash_in = $row->receive;
                $this->cash_out = $row->pay;
            } elseif ($row->method_id === 'bank') {
                $this->bank_transfer_in = $row->receive;
                $this->bank_transfer_out = $row->pay;
            }
            $this->cash_end = $this->cash_begin + $this->cash_in - $this->cash_out - $this->cash_to_safe - $this->cash_to_bank;
            $this->bank_transfer_end = $this->bank_transfer_in - $this->bank_transfer_out;
        }
        $rows = Payment::whereShiftId($this->id)->get();

        $this->pawn_amount = 0;
        $this->pawn_count = 0;
        foreach ($rows as $row) {
            if ($row->paymentable) {
                if (get_class($row->paymentable) === Pawn::class) {
                    if ($row->payment_type_id === 'paw') {
                        $this->pawn_amount += $row->paymentable->price;
                        $this->pawn_count += 1;
                    }
                }
            }
        }
    }

    static function current()
    {
        return Shift::whereStatus('open')->first();
    }
}
