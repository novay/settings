<?php

namespace Novay\Settings\Setting;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id', 'updated_at'];

    protected $table = 'settings';

    public function scopeGroup($query, string $groupName)
    {
        return $query->where('group', $groupName);
    }
}
