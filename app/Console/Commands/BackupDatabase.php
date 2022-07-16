<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $DB_HOST = env('DB_HOST', 'localhost');
            $DB_PORT = env('DB_PORT', 3306);
            $DB_NAME = env('DB_DATABASE', 'skp_billing');
            $DB_USER = env('DB_USERNAME', 'root');
            $DB_PASS = env('DB_PASSWORD', '123123');
            
            $filename = $DB_NAME . '_' . date("Y-m-d-H-i-s") . '.sql';

            $filebackup = public_path('/backup/database/' . $filename);

            $command = "mysqldump --opt -h $DB_HOST -u$DB_USER -p$DB_PASS --databases $DB_NAME > $filebackup";
            system($command);

            $this->info('The command was successful, filename is ' . $filebackup);
        } catch (\Exception $e) {
            $this->line($e->getMessage());

            $this->error('Something went wrong!');
        }

        return 0;
    }
}
