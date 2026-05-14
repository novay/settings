<?php

namespace Novay\Settings\Setting;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Novay\Kunci\Facades\Kunci;
use Illuminate\Support\Facades\Crypt;

class SettingEloquentStorage implements SettingStorage
{
    protected string $group = 'default';
    protected string $cacheKey = 'novay_settings';

    private function shouldEncrypt(): bool
    {
        return config('novay-settings.encrypt', false);
    }

    private function encrypt(mixed $value): string
    {
        if (!$this->shouldEncrypt()) {
            return $value;
        }

        return config('novay-settings.driver') === 'kunci'
            ? Kunci::encrypt($value)
            : Crypt::encryptString($value);
    }

    private function decrypt(mixed $value): mixed
    {
        if (!$this->shouldEncrypt() || !is_string($value)) {
            return $value;
        }

        try {
            return config('novay-settings.driver') === 'kunci'
                ? Kunci::decrypt($value)
                : Crypt::decryptString($value);
        } catch (\Exception) {
            return null;
        }
    }

    public function all(bool $fresh = false): Collection
    {
        if ($fresh) {
            return $this->modelQuery()->pluck('val', 'name');
        }

        return Cache::rememberForever($this->getCacheKey(), function () {
            return $this->modelQuery()->pluck('val', 'name');
        });
    }

    public function get(string $key, mixed $default = null, bool $fresh = false): mixed
    {
        $value = $this->all($fresh)->get($key, $default);
        return $this->decrypt($value);
    }

    public function set(string|array $key, mixed $value = null): mixed
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
            return true;
        }

        $setting = $this->getSettingModel()->firstOrNew([
            'name'  => $key,
            'group' => $this->group,
        ]);

        $setting->val   = $this->encrypt($value);
        $setting->group = $this->group;
        $setting->save();

        $this->flushCache();

        return $value;
    }

    public function has(string $key): bool
    {
        return $this->all()->has($key);
    }

    public function remove(string $key): bool
    {
        $deleted = $this->getSettingModel()->where('name', $key)->delete();
        $this->flushCache();
        return (bool) $deleted;
    }

    public function flushCache(): bool
    {
        return Cache::forget($this->getCacheKey());
    }

    public function group(string $groupName): self
    {
        $this->group = $groupName;
        return $this;
    }

    protected function getCacheKey(): string
    {
        return "{$this->cacheKey}.{$this->group}";
    }

    protected function getSettingModel(): Builder
    {
        return app(Setting::class)->query();
    }

    protected function modelQuery(): Builder
    {
        return $this->getSettingModel()->where('group', $this->group);
    }
}
