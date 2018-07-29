<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class MySqlDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the mysqldump utility using info from .env';
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ds = DIRECTORY_SEPARATOR;
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        $ts = time();
        $path = public_path() . $ds ."backupdb". $ds . 'http---diemrenluyenstu.cf-' . $ds . "dump" . $ds;
        $file = date('Y-m-d-His', $ts) . '-dump-' . $database . '.sql';
//        $command = sprintf('mysqldump -h %s -u %s -p\'%s\' %s > %s', $host, $username, $password, $database, $path . $file);
        $command = sprintf('mysqldump -h %s -u %s %s > %s', $host, $username, $database, $path . $file);
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        exec($command);
    }
}
