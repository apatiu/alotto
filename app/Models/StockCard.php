<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\HasTeams;

class StockCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'cost_wage', 'tag_wage',
        'cost_price', 'tag_price',
        'qty_begin', 'qty_in', 'qty_out', 'qty_end',
        'weight_begin', 'wt_in', 'wt_out', 'wt_end',
        'dt', 'ref_no', 'description'];

    protected $appends = ['team'];

    public function getTeamAttribute()
    {
        return $this->product()->first()->team;

    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ref()
    {
        return $this->morphTo();
    }

    static function add(Product $product, $qty, $ref = null)
    {
        DB::transaction(function () use ($product, $qty, $ref) {
            $latest = StockCard::whereProductId($product->id)->latest('dt')->first();
            if (!$latest) {
                $latest = StockCard::create([
                    'product_id' => $product->id,
                    'dt' => now()
                ]);
            }
            $card = $latest->replicate();
            $card->dt = $ref->dt ?? now();
            $card->cost_wage = $product->cost_wage;
            $card->tag_wage = $product->tag_wage;
            $card->cost_price = $product->cost_price;
            $card->tag_price = $product->tag_price;
            $card->qty_begin = $latest->qty_end;
            $card->qty_in = ($qty >= 0) ? $qty : 0;
            $card->qty_out = ($qty < 0) ? abs($qty) : 0;
            $card->qty_end = $card->qty_begin + $card->qty_in - $card->qty_out;
            $card->wt_begin = $latest->wt_end;
            $card->wt_in = ($qty >= 0) ? $qty * $product->wtGram : 0;
            $card->wt_out = ($qty < 0) ? abs($qty * $product->wtGram) : 0;
            $card->wt_end = $card->wt_begin + $card->wt_in - $card->wt_out;
            $card->user_id = Auth::user()->id;

            if ($ref) {
                $card->ref_type = get_class($ref);
                $card->ref_id = $ref->id;
            }

            $card->save();
            return $card;
        });


    }

}
