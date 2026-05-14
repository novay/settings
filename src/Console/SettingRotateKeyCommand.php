<?php

namespace Novay\Settings\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Novay\Kunci\Facades\Kunci;
use Novay\Settings\Facade;
use Novay\Settings\Setting\Setting;

class SettingRotateKeyCommand extends Command
{
    protected $signature = 'setting:rotate-key {--force}';
    protected $description = 'Re-encrypt semua settings dengan key baru (setelah ganti APP_KEY atau kunci config)';

    public function handle()
    {
        if (!config('settings.encrypt')) {
            $this->warn('Encryption sedang OFF. Tidak ada yang perlu di-rotate.');
            return;
        }

        $this->warn("⚠️  Pastikan Anda sudah mengganti APP_KEY atau konfigurasi kunci!");

        if (!$this->option('force') && !$this->confirm('Lanjutkan rotasi encryption key?')) {
            return;
        }

        $settings = Setting::all();
        $success = 0;

        foreach ($settings as $setting) {
            try {
                $decrypted = config('settings.driver') === 'kunci'
                    ? Kunci::decrypt($setting->val)
                    : Crypt::decryptString($setting->val);

                $setting->val = config('settings.driver') === 'kunci'
                    ? Kunci::encrypt($decrypted)
                    : Crypt::encryptString($decrypted);

                $setting->save();
                $success++;
            } catch (\Exception $e) {
                $this->error("Gagal rotate {$setting->group}.{$setting->name}");
            }
        }

        Facade::flushCache();
        $this->info("✅ Rotasi selesai! {$success} setting berhasil di-encrypt ulang.");
    }
}
