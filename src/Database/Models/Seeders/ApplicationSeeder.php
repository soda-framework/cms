<?php

namespace Soda\Cms\Database\Models\Seeders;

use Illuminate\Database\Seeder;
use Soda\Cms\Database\Models\Application;
use Soda\Cms\Database\Models\ApplicationUrl;

class ApplicationSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $baseName = str_slug(basename(base_path()), '-');
        if ($baseName == 'src') {
            $baseName = str_slug(basename(dirname(base_path())), '-');
        }

        $application = Application::firstOrCreate([
            'name' => ucwords(str_replace('-', '', $baseName)),
        ]);

        ApplicationUrl::firstOrCreate([
            'domain'         => $baseName.'.dev',
            'application_id' => $application->getKey(),
        ]);
    }
}
