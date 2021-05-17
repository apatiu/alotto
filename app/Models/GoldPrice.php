<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class GoldPrice extends Model
{
    use HasFactory;

    protected $fillable = ['dt', 'gold_price_sale', 'gold_price_buy', 'gold_price_diff', 'gold_price_tax'];

    static public function now()
    {
        $data = goldPriceOrg();
        $output = Arr::first($data, function ($value, $key) {
            return $value->name == 'สมาคมฯ';
        });
        $update = Arr::first($data, function ($value, $key) {
            return $value->name == 'Update';
        });
        $model = GoldPrice::latest()->first();
        if (!$model) {
            $model = GoldPrice::create(
                [
                    'dt' => Carbon::createFromTimestamp($update->bid)->toDateString(),
                    'gold_price_sale' => $output->ask,
                    'gold_price_buy' => $output->bid,
                    'gold_price_diff' => $output->diff,
                    'gold_price_tax' => round(($output->bid - ($output->bid * (1.8 / 100))) / 15.16) * 15.16
                ]
            );
        } else {
            if (floatval($model->gold_price_sale) !== floatval($model->gold_price_sale)) {
                $model = GoldPrice::create([
                    'dt' => Carbon::createFromTimestamp($update->bid)->toDateString(),
                    'gold_price_sale' => $output->ask,
                    'gold_price_buy' => $output->bid,
                    'gold_price_diff' => $output->diff,
                    'gold_price_tax' => round(($output->bid - ($output->bid * (1.8 / 100))) / 15.16) * 15.16
                ]);
            }
        }

        return $model;
    }

}
