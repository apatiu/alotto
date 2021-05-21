<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'initial',
        'name',
        'team_id',
        'contact_person',
        'birthday',
        'tax_id',
        'address',
        'city',
        'district',
        'province',
        'country',
        'postal_code',
        'idcard_start',
        'idcard_end',
        'idcard_place',
        'email',
        'phone'];

    protected $appends = ['id_label'];

    use HasFactory;

    public function getIdLabelAttribute()
    {
        return 'C' . substr('0000' . $this->id, -5);
    }
}
