<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'team_id'];

    protected $appends = ['id_label'];

    use HasFactory;

    public function getIdLabelAttribute()
    {
        return 'C' . substr('0000' . $this->id, -5);
    }
}
