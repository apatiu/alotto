<?php


namespace App\Http\Helpers;


use App\Models\Meta;

class MetaHelper
{
    static public function get($key, $default = null)
    {
        return Meta::whereMetaKey($key)->first()->value ?? $default;
    }

    static public function set($key, $value)
    {
        return Meta::updateOrCreate(
            ['meta_key' => $key],
            ['value' => $value]
        );
    }
}
