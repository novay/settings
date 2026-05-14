<?php

if (! function_exists('settings')) {
    function settings($key = null, $default = null)
    {
        $setting = app('Novay\Settings\Setting\SettingStorage');

        if (is_null($key)) {
            return $setting;
        }

        if (is_array($key)) {
            return $setting->set($key);
        }

        return $setting->get($key, value($default));
    }
}
