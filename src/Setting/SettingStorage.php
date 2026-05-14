<?php

namespace Novay\Settings\Setting;

use Illuminate\Support\Collection;

interface SettingStorage
{
    public function all(bool $fresh = false): Collection;
    public function get(string $key, mixed $default = null, bool $fresh = false): mixed;
    public function set(string|array $key, mixed $value = null): mixed;
    public function has(string $key): bool;
    public function remove(string $key): bool;
    public function flushCache(): bool;
    public function group(string $groupName): self;
}
