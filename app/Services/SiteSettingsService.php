<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingsService
{
    protected $cacheKey = 'site_settings';

    public function get($key = null)
    {
        $settings = Cache::rememberForever($this->cacheKey, function () {
            return SiteSetting::all()->pluck('value', 'name')->toArray();
        });

        return $key ? ($settings[$key] ?? null) : $settings;
    }

    public function clear()
    {
        Cache::forget($this->cacheKey);
    }

    public function set($key, $value)
    {
        SiteSetting::updateOrCreate(['name' => $key], ['value' => $value]);
        $this->clear();
    }
}
