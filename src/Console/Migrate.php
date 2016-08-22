<?php

namespace Soda\Cms\Console;

use Illuminate\Console\Command;

class Migrate extends Command {

    protected $signature = 'soda:migrate';
    protected $description = 'Migrate the Soda Database';
    protected $except = [];

    public function handle() {
        $this->call('db:seed', [
            '--path' => '/vendor/soda-framework/cms/database/migrations'
        ]);
    }
}
