<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['id', 'name'];
    public $incrementing = false;
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($sup) {
            if (is_null($sup->id)) {
                $latest = Supplier::orderBy('id', 'desc')
                    ->select('id')
                    ->first();
                $latest = $latest->attributes['id'] ?? '000000';
                $latest = intval(substr($latest, -5));

                $sup->id = 'S' . substr('0000' . ($latest + 1), -5);

            }
        });
    }
}
