<?php

use Illuminate\Database\Seeder;
use Soda\Cms\Models\Application;
use Soda\Cms\Models\ApplicationUrl;

class SodaApplicationSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $application = Application::create([
            'name' => 'Soda CMS',
        ]);

        ApplicationUrl::create([
            'domain'         => 'localhost:8000',
            'application_id' => $application->id,
        ]);
    }
}
