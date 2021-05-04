<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "team_id",
        "product_id",
        "team_id",
        "gold_percent",
        "product_type_id",
        "product_design_id",
        "size",
        "name",
        "min_qty",
        "weight",
        "weightbaht",
        "cost_wage",
        "cost_price",
        "tag_wage",
        "tag_price",
        "avr_cost_per_baht",
        "sale_with_gold_price",
        "wage_by_pcs",
        "qty",
        "description"
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function stockCards()
    {
        return $this->hasMany(StockCard::class);
    }

    public function getGoldPercentAttribute() {
        return GoldPercent::whereId($this->attributes['gold_percent'])->first();
    }

    public function gen_product_id()
    {
        $gold_percent = $this->attributes['gold_percent'];
        $product_type_id = $this->attributes['product_type_id'];
        $product_weight = $this->weightbaht ? $this->weight * 15.2 : $this->weight;
        $product_design = $this->product_design_id ?? null;
        $product_size = $this->size;

        $product_id = $gold_percent . $product_type_id . $product_weight .
            ($product_design ? 'D' . $product_design : '') .
            ($product_size ? 'S' . $product_size : '');
        $this->attributes['product_id'] = $product_id;
    }

    public function gen_product_name()
    {
        if (!$this->product_id)
            return;

        $result = [];

        array_push($result, ProductType::find($this->product_type_id)->name);

        if ($this->weight) {
            $w = '';
            if ($this->weightbaht) {
                if ($this->weight < 1) {
                    $w = ($this->weight / 0.25) . ' สลึง';
                } else {
                    $w = $this->weight . ' บาท';
                }
            } else {
                $w = $this->weight . ' กรัม';
            }
            array_push($result, $w);
        }

        if ($this->product_design_id) {
            $design_name = ProductDesign::find($this->product_design_id)->name;
            array_push($result, $design_name);
        }

        if ($this->size) {
            array_push($result, 'ขนาด ' . $this->size);
        }

        $this->attributes['name'] = implode(' ', $result);

    }
}
