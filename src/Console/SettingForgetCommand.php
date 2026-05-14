<?php

namespace Novay\Settings\Console;

use Illuminate\Console\Command;
use Novay\Settings\Facade;

class SettingForgetCommand extends Command
{
    protected $signature = 'setting:forget 
        {key : Nama setting}
        {--group=default : Group setting}';

    protected $description = 'Hapus sebuah setting';

    public function handle()
    {
        $key   = $this->argument('key');
        $group = $this->option('group');

        if (!Facade::group($group)->has($key)) {
            $this->warn("Setting '{$key}' tidak ditemukan.");
            return;
        }

        if ($this->confirm("Yakin ingin menghapus '{$key}'?")) {
            Facade::group($group)->remove($key);
            $this->info("✅ Setting '{$key}' berhasil dihapus!");
        }
    }
}
