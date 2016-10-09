<?php

namespace Soda\Cms\Seeds;

use Illuminate\Database\Seeder;
use Soda\Cms\Support\Constants;
use Soda\Cms\Models\Application;
use Soda\Cms\Models\BlockType;

class BlockSeeder extends Seeder
{

    /**
     * Auto generated seed file
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
            'status'           => Constants::STATUS_LIVE,
            'edit_action'      => 'view',
            'edit_action_type' => 'soda::blocks.index',
        ]);
    }
}
