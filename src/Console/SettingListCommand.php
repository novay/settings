<?php

namespace Novay\Settings\Console;

use Illuminate\Console\Command;
use Novay\Settings\Facade;

class SettingListCommand extends Command
{
    protected $signature = 'setting:list 
        {--group=default : Group yang ditampilkan}
        {--fresh : Ambil langsung dari DB (ignore cache)}';

    protected $description = 'Tampilkan semua settings';

    public function handle()
    {
        $group = $this->option('group');
        $fresh = $this->option('fresh');

        $settings = Facade::group($group)->all($fresh);

        if ($settings->isEmpty()) {
            $this->warn("Tidak ada settings di group '{$group}'");
            return;
        }

        $rows = $settings->map(fn($value, $key) => [
            $key,
            config('novay-settings.encrypt') ? '********' : (strlen($value) > 60 ? substr($value, 0, 57) . '...' : $value),
        ])->toArray();

        $this->table(['Key', 'Value'], $rows);
        $this->info("\nTotal: {$settings->count()} setting(s) | Group: {$group}");
    }
}
