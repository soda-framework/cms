<?php

use Soda\Cms\Models\BlockType;
use Illuminate\Database\Seeder;
use Soda\Cms\Components\Status;
use Soda\Cms\Models\Application;

class SodaBlockSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $application = Application::first();
        $slider = BlockType::create([
            'name'             => 'Slider',
            'description'      => 'Slides to appear in a slider plugin',
            'application_id'   => $application->id,
            'action'           => 'partials.sections.slider',
            'action_type'      => 'view',
            'package'          => 'sodacms',
            'identifier'       => 'slider',
            'status'           => Status::LIVE,
            'edit_action'      => 'view',
            'edit_action_type' => 'soda::blocks.index',
        ]);
    }
}
