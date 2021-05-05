<?php
if (!function_exists('goldprice')) {
    function goldprice()
    {
        try {

            header('Access-Control-Allow-Origin: *');

            $output = file_get_contents('http://thaigold.info/RealTimeDataV2/gtdata_.txt');
            \Illuminate\Support\Facades\Log::info($output);
            $output = json_decode($output, false);
            $output = Arr::first($output, function ($value, $key) {
                return $value->name == 'สมาคมฯ';
            });

            $gbSell = floatval($output->ask);
            $gbBuy = floatval($output->bid);
            \Illuminate\Support\Facades\Log::info($output);

            return $gbSell;
        } catch (\Exception $exception) {
            return 0;
        }
    }
}

if (!function_exists('weightgram')) {
    function weightgram($w, $wb)
    {
        return $wb ? ($w * 15.2) : $w;
    }
}

if (!function_exists('jsDateToDateString')) {
    function jsDateToDateString($dt)
    {
        return \Carbon\Carbon::create($dt)
            ->timezone(config('app.timezone'))
            ->toDateString();
    }
}

if (!function_exists('jsDateToDateTimeString')) {
    function jsDateToDateTimeString($dt)
    {
        return \Carbon\Carbon::create($dt)
            ->timezone(config('app.timezone'))
            ->toDateTimeString();
    }
}
