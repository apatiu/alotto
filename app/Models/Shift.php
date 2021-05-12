<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'cash',
        'cash_begin', 'cash_to_safe',
        'cash_to_bank',
        'cash_end',
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

    }

    static function current() {
        return Shift::whereStatus('open')->first();
    }
}
