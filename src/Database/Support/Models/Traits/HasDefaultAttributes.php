<?php

namespace Soda\Cms\Database\Support\Models\Traits;

trait HasDefaultAttributes
{
    public function fillDefaults()
    {
        foreach ($this->defaults as $default => $defaultValue) {
            if (! isset($this->attributes[$default])) {
                $this->setAttribute($default, $defaultValue);
            }
        }

        return $this;
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (($value === null || $value === '') && property_exists($this, 'defaults') && array_key_exists($key, $this->defaults)) {
            $value = $this->defaults[$key];
        }

        return $value;
    }

    /**
     * Get the fillable attributes of a given array. Don't store the defaults.
     *
     * @param  array $attributes
     *
     * @return array
     */
    protected function fillableFromArray(array $attributes)
    {
        if (! empty($attributes) && property_exists($this, 'defaults')) {
            $attributes = array_diff_assoc($attributes, $this->defaults);
        }

        return parent::fillableFromArray($attributes);
    }
}
