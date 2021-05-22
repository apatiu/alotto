<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OldGoldStockCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'gold_percent_id', 'team_id',
        'avg_per_baht',
        'qty_begin', 'qty_in', 'qty_out', 'qty_end',
        'wt_begin', 'wt_in', 'wt_out', 'wt_end',
        'description', 'dt',
        'ref_id', 'ref_type'];

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

    static public function add($gold_percent_id, $qty, $wt, $price, $ref, $desc = '', $team_id = null)
    {
        DB::transaction(function () use ($gold_percent_id, $qty, $wt, $price, $ref, $desc, $team_id) {
            if (!$team_id)
                $team_id = request()->user()->currentTeam->id;

            $avg_per_baht = ($price / $wt) * 15.2;

            $sc = OldGoldStockCard::where('gold_percent_id', '=', $gold_percent_id)
                ->whereTeamId($team_id)
                ->latest('dt')
                ->first();

            if (!$sc) {
                $sc = new OldGoldStockCard([
                    'gold_percent_id' => $gold_percent_id,
                    'team_id' => $team_id,
                    'avg_per_baht' => $avg_per_baht,
                    'qty_begin' => 0,
                    'qty_in' => $qty > 0 ? $qty : 0,
                    'qty_out' => $qty > 0 ? 0 : $qty,
                    'qty_remain' => $qty,
                    'wt_begin' => 0,
                    'wt_in' => $wt > 0 ? $wt : 0,
                    'wt_out' => $wt > 0 ? 0 : $wt,
                    'wt_end' => $wt,
                    'dt' => now(),
                    'ref_id' => $ref->id,
                    'ref_type' => get_class($ref),
                    'description' => $desc
                ]);
                $sc->save();
                return $sc;
            } else {
                $new = $sc->replicate();
                $new->description = $desc;
                $new->qty_begin = $new->qty_end;
                $new->qty_in = $qty > 0 ? $qty : 0;
                $new->qty_out = $qty > 0 ? 0 : $qty;
                $new->qty_end = $new->qty_begin + $new->qty_in - $new->qty_out;
                $new->wt_begin = $new->wt_end;
                $new->wt_in = $wt > 0 ? $wt : 0;
                $new->wt_out = $wt > 0 ? 0 : $wt;
                $new->wt_end = $new->wt_begin + $new->wt_in - $new->wt_out;
                $new->dt = now();
                $new->ref_id = $ref->id;
                $new->ref_type = get_class($ref);
                $new->save();
                return $new;
            }

        });
    }
}
