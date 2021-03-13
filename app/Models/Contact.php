<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['id','name'];

    public function getIdAttribute($value) {
        return substr('0000' . $value,-5);
    }
    public function roles() {
        return $this->belongsToMany((ContactRole::class));
    }
}
