<?php

namespace Novay\Settings\Console;

use Illuminate\Console\Command;
use Novay\Settings\Facade;

class SettingSetCommand extends Command
{
    protected $signature = 'setting:set 
        {key : Nama setting}
        {value? : Nilai setting}
        {--group=default : Group setting}';

    protected $description = 'Set atau update sebuah setting';

    public function handle()
    {
        $key   = $this->argument('key');
        $value = $this->argument('value') ?? $this->ask("Masukkan nilai untuk {$key}");
        $group = $this->option('group');

        Facade::group($group)->set($key, $value);

        $this->info("✅ Setting '{$key}' berhasil disimpan di group '{$group}'" .
            (config('settings.encrypt') ? ' (encrypted)' : ''));
    }
}
