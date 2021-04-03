<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $fillable = ['type','media','datatext','path'];

    public function pawn_items() {
        return $this->morphedByMany(PawnItem::class,'mediable');
    }
}
