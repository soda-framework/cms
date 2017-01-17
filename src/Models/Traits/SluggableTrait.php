<?php

namespace Soda\Models\Traits;

use Illuminate\Support\Str;

trait SluggableTrait
{
    /**
     * takes a parent tree item and generates a slug based off it.
     * @param $item
     * @return string
     */
    public function generateSlug($title)
    {
        $slug = $this->slug.'/'.Str::slug($title);
        //make sure it doesn't already exist
        if (self::where('slug', "$slug")->first()) {
            //it exists already.. we have to find the highest slug and increment by 1.
            $highest = self::where('slug', 'like', "$slug-%")->orderBy('slug', 'desc')->first();
            $num = 1;
            if ($highest) {
                $num = str_replace("$slug-", '', $highest->slug);
                $num++;
            }

            return "$slug-$num";
        }

        return $slug;
    }
}
