<?php

namespace Soda\Cms\Console;

use Config;
use DB;
use Illuminate\Console\Command;
use PDO;

class Setup extends Command
{

    protected $signature = 'soda:setup {--f|no-filesystem : Skip filesystem config setup} {--e|no-env : Skip environment variable setup} {--d|no-database : Skip database environment variable setup}';
    protected $description = 'Initial setup command for the Soda Framework';
    protected $except = [];

    public function handle()
    {
        if (!$this->option('no-env')) {
            $this->updateEnv();
        }

        if (!$this->option('no-filesystem')) {
            $this->updateConfig();
        }
    }

    protected function updateEnv()
    {
        $environment_file_path = base_path('.env');

        if (file_exists($environment_file_path)) {
            $contents = file_get_contents($environment_file_path);
            if (!$this->option('no-database')) {
                $base_name = str_slug(basename(base_path()));
                if ($base_name == 'src') {
                    $base_name = str_slug(basename(dirname(base_path())), '-');
                }
                $db_host = $this->ask('Database host', 'localhost');
                $db_name = $this->ask('Database name', $base_name);
                $db_user = $this->ask('Database user', 'root');
                $db_pass = $this->ask('Database password', false);

                $contents = preg_replace('/DB_HOST=(.*)/', 'DB_HOST='.$db_host, $contents);
                $contents = preg_replace('/DB_DATABASE=(.*)/', 'DB_DATABASE='.$db_name, $contents);
                $contents = preg_replace('/DB_USERNAME=(.*)/', 'DB_USERNAME='.$db_user, $contents);
                $contents = preg_replace('/DB_PASSWORD=(.*)/', 'DB_PASSWORD='.$db_pass, $contents);

                Config::set('database.connections.mysql.host', $db_host);
                Config::set('database.connections.mysql.database', $db_name);
                Config::set('database.connections.mysql.username', $db_user);
                Config::set('database.connections.mysql.password', $db_pass);

                $connection = new PDO("mysql:host={$db_host}", $db_user, $db_pass);
                $connection->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}`");
            }

            $contents = str_replace('CACHE_DRIVER=file', 'CACHE_DRIVER=array', $contents);
            $contents = str_replace('SESSION_DRIVER=file', 'SESSION_DRIVER=database', $contents);
            file_put_contents($environment_file_path, $contents);
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
