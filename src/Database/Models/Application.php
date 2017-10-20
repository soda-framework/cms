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
        'default_language',
        'append_css',
        'css_url',
        'logo_url',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function content()
    {
        return $this->hasMany(Content::class);
    }

    public function settings()
    {
        return $this->hasMany(ApplicationSetting::class);
    }

    public function urls()
    {
        return $this->hasMany(ApplicationUrl::class);
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

    public function getSettings()
    {
        return app('soda.application.repository')->getSettingsForApplication($this);
    }
}
