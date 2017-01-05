<?php

namespace Soda\Cms\Seeds;

use Illuminate\Database\Seeder;
use Soda\Cms\Models\Application;
use Soda\Cms\Models\ApplicationUrl;

class ApplicationSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $baseName = str_slug(basename(base_path()), '-');
        if ($baseName == 'src') {
            $baseName = str_slug(basename(dirname(base_path())), '-');
        }

        $application = Application::create([
            'name' => ucwords(str_replace('-', '', $baseName)),
        ]);

        ApplicationUrl::create([
            'domain'         => $baseName.'.dev',
            'application_id' => $application->id,
        ]);
    }
}
