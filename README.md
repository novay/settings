# Laravel Settings

Store key-value settings in database with encryption support.

## Installation

```bash
composer require novay/settings
php artisan migrate
php artisan vendor:publish --tag=novay-settings-config
```

Tambahkan di `.env`:

```env
SETTINGS_ENCRYPT=true
SETTINGS_ENCRYPTION_DRIVER=laravel   # atau kunci
```

## Usage

```php
settings()->set('app_name', 'My App');
settings('app_name');

settings()->group('payment')->set('midtrans_key', 'sk-xxx');

@setting('app_name')          // Blade
```

**Artisan Commands**

```bash
php artisan setting:set app_name "My App"
php artisan setting:list
php artisan setting:forget api_key
php artisan setting:rotate-key
```

## License

MIT License.