<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PawnItem extends Model
{
    use HasFactory;

    protected $fillable = ['pawn_id', 'gold_percent_id', 'product_type', 'weight', 'price'];
    protected $appends = ['img'];

    public function pawn()
    {
        return $this->belongsTo(Pawn::class);
    }

    public function images()
    {
        return $this->morphToMany(Media::class, 'mediable');
    }

    public function getImgAttribute()
    {
        return $this->images()->first()->datatext ?? null;
    }
}
