<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldGoldStockCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'gold_percent_id', 'team_id',
        'avg_per_bath',
        'qty_begin', 'qty_in', 'qty_out', 'qty_end',
        'wt_begin', 'wt_in', 'wt_out', 'wt_end',
        'description', 'dt',
        'ref_id','ref_type'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function gold_percent()
    {
        return $this->belongsTo(GoldPercent::class);
    }

    public function ref()
    {
        return $this->morphTo();
    }
}
