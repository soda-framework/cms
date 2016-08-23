<?php

namespace Soda\Cms\Models\Traits;

use Illuminate\Support\Str;

trait SluggableTrait {

    /**
     * takes a parent tree item and generates a slug based off it.
     *
     * @param $title
     *
     * @return string
     */
    public function generateSlug($title) {
        $slug = $this->fixSlug($this->slug ) . $this->fixSlug($title);
        //make sure it doesn't already exist
        if (static::where('slug', "$slug")->first()) {
            //it exists already.. we have to find the highest slug and increment by 1.
            $highest = static::where('slug', 'like', "$slug-%")->orderBy('slug', 'desc')->first();
            $num = 1;
            if ($highest) {
                $num = str_replace("$slug-", "", $highest->slug);
                $num++;
            }

            return $slug . '-' . $num;
        }

        return $slug;
    }

    public function fixSlug($slug) {
        $parts = explode('/', $slug);
        $slug = '';

        foreach($parts as $part) {
            if($part) $slug .= '/' . str_slug($part);
        }

        return $slug;
    }
}
