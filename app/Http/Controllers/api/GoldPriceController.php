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
            GoldPrice::create(
                [
                    'dt' => Carbon::createFromTimestamp($update->bid)->toDateString(),
                    'ask' => $output->ask,
                    'bid' => $output->bid,
                    'diff' => $output->diff
                ]
            );
        } else {
            if (floatval($model->ask) !== floatval($output->ask)) {
                GoldPrice::create([
                    'dt' => Carbon::createFromTimestamp($update->bid)->toDateString(),
                    'ask' => $output->ask,
                    'bid' => $output->bid,
                    'diff' => $output->diff
                ]);
            }
        }

        return ['ask' => $output->ask, 'bid' => $output->bid];
    }

    public function loadFromOrg()
    {
        $output = file_get_contents('http://thaigold.info/RealTimeDataV2/gtdata_.txt');
        $output = json_decode($output, false);
        return ($output);
    }
}
