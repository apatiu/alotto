<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bank', 'acc_no', 'acc_name', 'qr_code'];

    public function scopeOfTeam($query, $team_id)
    {
        return $query->whereTeamId($team_id);
    }
}
