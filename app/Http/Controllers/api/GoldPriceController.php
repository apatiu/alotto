<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoldPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class GoldPriceController extends Controller
{
    public function now()
    {
        $data = $this->loadFromOrg();
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

    public function loadFromOrg()
    {
        $output = file_get_contents('http://thaigold.info/RealTimeDataV2/gtdata_.txt');
        $output = json_decode($output, false);
        return ($output);
    }
}
