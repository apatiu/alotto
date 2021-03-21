<?php


namespace App\Http\Helpers;


use Illuminate\Support\Arr;

class GoldPriceHelper
{
    static public function GoldPrice() {
        try {

            header('Access-Control-Allow-Origin: *');

            $output = file_get_contents('http://thaigold.info/RealTimeDataV2/gtdata_.txt');
            $output = json_decode($output, false);
            $output = Arr::first($output, function ($value, $key) {
                return $value->name == 'สมาคมฯ';
            });

            $gbSell = floatval($output->ask);
            $gbBuy = floatval($output->bid);

            return $gbSell;
        } catch (\Exception $exception) {
            return 0;
        }
    }
}
