<?php

namespace Soda\Cms\Database\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;

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
        return $this->hasMany(resolve_class('soda.page.model'));
    }

    public function settings()
    {
        return $this->hasMany(resolve_class('soda.application-setting.model'));
    }

    public function urls()
    {
        return $this->hasMany(resolve_class('soda.application-url.model'));
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
