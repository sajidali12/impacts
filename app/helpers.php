<?php

use App\Models\Setting;

if (!function_exists('settings')) {
    function settings(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}