<?php

namespace Novay\Settings;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->publishes([
            __DIR__ . '/../migrations/' => database_path('migrations'),
        ], 'settings-migrations');

        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('settings.php'),
        ], 'settings-config');

        // Blade Directive
        Blade::directive('setting', function ($expression) {
            return "<?php echo settings({$expression}); ?>";
        });

        // Register Artisan Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\SettingSetCommand::class,
                Console\SettingListCommand::class,
                Console\SettingForgetCommand::class,
                Console\SettingRotateKeyCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'settings');

        $this->app->singleton('Novay\Settings\Setting\SettingStorage', function () {
            return new Setting\SettingEloquentStorage();
        });
    }
}
