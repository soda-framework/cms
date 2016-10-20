<?php

namespace Soda\Cms\Database\Support\Seeders;

use Illuminate\Database\Seeder;
use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationUrlInterface;

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

        $application = app('soda.application.model')->create([
            'name' => ucwords(str_replace('-', '', $baseName)),
        ]);

        app(ApplicationUrlInterface::class)->create([
            'domain'         => $baseName.'.dev',
            'application_id' => $application->getKey(),
        ]);
    }
}
