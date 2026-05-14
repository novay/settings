<?php

namespace Novay\Settings;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    protected static function getFacadeAccessor()
    {
        return 'Novay\Settings\Setting\SettingStorage';
    }
}
