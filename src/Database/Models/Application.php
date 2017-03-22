<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Contracts\ApplicationInterface;

class Application extends Model implements ApplicationInterface
{
    protected $table = 'applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function settings()
    {
        return $this->hasMany(ApplicationSetting::class);
    }

    public function urls()
    {
        return $this->hasMany(ApplicationUrl::class);
    }

    public function getSettings()
    {
        return app('soda.application.cached-repository')->getSettingsForApplication($this);
    }

    public function getSetting($setting)
    {
        $settings = $this->getSettings();
        if ($settings) {
            $setting = $settings->where('field_name', $setting)->first();
            if ($setting) {
                return $setting->getFieldValue();
            }
        }
    }
}
