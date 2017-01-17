<?php

namespace Soda\Cms\Console;

use DB;
use Config;
use Illuminate\Console\Command;

class Setup extends Command
{
    protected $signature = 'soda:setup {--f|no-filesystem : Skip filesystem config setup} {--e|no-env : Skip environment variable setup} {--d|no-database : Skip database environment variable setup}';
    protected $description = 'Initial setup command for the Soda Framework';

    public function handle()
    {
        if (! $this->option('no-env')) {
            $this->updateEnv();
        }

        if (! $this->option('no-filesystem')) {
            $this->updateConfig();
        }
    }

    protected function updateEnv()
    {
        $envFilePath = base_path('.env');

        if (file_exists($envFilePath)) {
            $contents = file_get_contents($envFilePath);
            if (! $this->option('no-database')) {
                $baseName = str_slug(basename(base_path()), '-');
                if ($baseName == 'src') {
                    $baseName = str_slug(basename(dirname(base_path())), '-');
                }
                $dbHost = $this->ask('Database host', '127.0.0.1');
                $dbPort = $this->ask('Database port', '3306');
                $dbName = $this->ask('Database name', $baseName);
                $dbUser = $this->ask('Database user', 'root');
                $dbPass = $this->ask('Database password', false);

                $contents = preg_replace('/DB_HOST=(.*)/', 'DB_HOST='.$dbHost, $contents);
                $contents = preg_replace('/DB_PORT=(.*)/', 'DB_PORT='.$dbPort, $contents);
                $contents = preg_replace('/DB_DATABASE=(.*)/', 'DB_DATABASE='.$dbName, $contents);
                $contents = preg_replace('/DB_USERNAME=(.*)/', 'DB_USERNAME='.$dbUser, $contents);
                $contents = preg_replace('/DB_PASSWORD=(.*)/', 'DB_PASSWORD='.$dbPass, $contents);

                file_put_contents($envFilePath, $contents);

                Config::set('database.connections.mysql.host', $dbHost);
                Config::set('database.connections.mysql.port', $dbPort);
                Config::set('database.connections.mysql.database', null);
                Config::set('database.connections.mysql.username', $dbUser);
                Config::set('database.connections.mysql.password', $dbPass);

                DB::purge('mysql');

                DB::connection('mysql')->getPdo()->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}`");
            }

            $contents = str_replace('SESSION_DRIVER=file', 'SESSION_DRIVER=database', $contents);
            file_put_contents($envFilePath, $contents);
        }
    }

    protected function updateConfig()
    {
        $config_path = config_path('filesystems.php');

        if (file_exists($config_path)) {
            $contents = file_get_contents($config_path);
            $contents = str_replace("'key' => 'your-key'", "'key' =>    env('AWS_ACCESS_KEY_ID')", $contents);
            $contents = str_replace("'secret' => 'your-secret'", "'secret' => env('AWS_SECRET_ACCESS_KEY')", $contents);
            $contents = str_replace("'region' => 'your-region'", "'region' => env('AWS_REGION')", $contents);
            $contents = str_replace("'bucket' => 'your-bucket'", "'bucket' => env('AWS_S3_BUCKET')", $contents);
            file_put_contents($config_path, $contents);
        }
    }
}
