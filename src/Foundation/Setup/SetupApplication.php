<?php

namespace Soda\Cms\Foundation\Setup;

use Illuminate\Database\Seeder;
use Soda\Cms\Database\Models\Application;
use Soda\Cms\Database\Models\ApplicationUrl;

class SetupApplication extends Seeder
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

        $application = Application::withoutGlobalScopes()->firstOrCreate([
            'name' => ucwords(str_replace('-', ' ', $baseName)),
        ]);

        ApplicationUrl::withoutGlobalScopes()->firstOrCreate([
            'domain'         => $baseName.'.test',
            'application_id' => $application->getKey(),
        ]);
    }
}
