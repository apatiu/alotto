<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "team_id",
        "code",
        "team_id",
        "gold_percent_id",
        "product_type_id",
        "product_design_id",
        "product_size_id",
        "name",
        "min_qty",
        "weight",
        "weightbaht",
        "cost_wage",
        "cost_price",
        "tag_wage",
        "tag_price",
        "avg_cost_per_baht",
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

    public function gold_percent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GoldPercent::class);
    }

    public function getCode()
    {
        $gold_percent_id = $this->attributes['gold_percent_id'];
        $product_type_id = $this->attributes['product_type_id'];
        $product_weight = $this->weightbaht ? $this->weight * 15.2 : $this->weight;
        $product_design_id = $this->product_design_id ?? null;
        $product_size_id = $this->product_size_id;

        $code = $gold_percent_id . $product_type_id . $product_weight .
            ($product_design_id ? 'D' . $product_design_id : '') .
            ($product_size_id ? 'S' . $product_size_id : '');
        $this->attributes['code'] = $code;
    }

    public function genName()
    {
        if (!$this->code)
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
