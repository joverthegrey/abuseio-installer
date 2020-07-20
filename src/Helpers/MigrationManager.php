<?php

namespace AbuseIO\AbuseIOInstaller\Helpers;

use Exception;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\BufferedOutput;
use Log;

class MigrationManager
{
    /**
     * MigrationManager constructor.
     */
    public function __construct()
    {
        // if not defined (include the base_path() for the binary)
        if (!defined('ARTISAN_BINARY')) {
            define('ARTISAN_BINARY', base_path() . '/artisan');
        }
    }

    /**
     * Starts the migration background process.
     *
     * @return array
     */
    public function startMigration()
    {
        $outputLog = new BufferedOutput;

        $this->sqlite($outputLog);

        return $this->migrate($outputLog);
    }

    /**
     * Starts the seeded background process.
     *
     * @return array
     */
    public function startSeeding()
    {
        $outputLog = new BufferedOutput;

        $this->sqlite($outputLog);

        return $this->seed($outputLog);
    }

    /**
     * Run the migration.
     *
     * @param \Symfony\Component\Console\Output\BufferedOutput $outputLog
     * @return array
     */
    private function migrate(BufferedOutput $outputLog)
    {

        $migratedFile = storage_path() . '/migrated';
        try{
            Log::debug('Starting migrations');
            call_in_background('migrate --force', null, 'touch ' . $migratedFile);
        }
        catch(Exception $e){
            return $this->response($e->getMessage(), 'error', $outputLog);
        }

        return $this->response('starting migration', 'success', $outputLog);
    }

    /**
     * Seed the database.
     *
     * @param \Symfony\Component\Console\Output\BufferedOutput $outputLog
     * @return array
     */
    private function seed(BufferedOutput $outputLog)
    {
        $seededFile = storage_path() . '/seeded';
        try{
            Log::debug('Starting seeding');
            call_in_background('db:seed --force', null, 'touch ' . $seededFile);
        }
        catch(Exception $e){
            return $this->response($e->getMessage(), 'error', $outputLog);
        }

        return $this->response('starting seeding', 'success', $outputLog);
    }

    /**
     * Edit the administrator
     */
    public function addAdmin($email, $password)
    {
        $email = escapeshellarg($email);
        $password = escapeshellarg($password);
        $adminFile = storage_path() . '/adminadded';

        Log::debug("email: $email password: $password file: $adminFile");

        try{
            Log::debug('Creating admin');
            call_in_background("user:edit --email=$email --password=$password 1", null, 'touch ' . $adminFile);
        }
        catch(Exception $e){
            return $this->response($e->getMessage(), 'error', null);
        }

        return $this->response('created admin', 'success', null);
    }

    /**
     * Return a formatted error messages.
     *
     * @param string $message
     * @param string $status
     * @param \Symfony\Component\Console\Output\BufferedOutput $outputLog
     * @return array
     */
    private function response($message, $status = 'danger', BufferedOutput $outputLog)
    {
        return [
            'status' => $status,
            'message' => $message,
            'dbOutputLog' => is_null($outputLog) ? null : $outputLog->fetch()
        ];
    }

    /**
     * Check database type. If SQLite, then create the database file.
     *
     * @param \Symfony\Component\Console\Output\BufferedOutput $outputLog
     */
    private function sqlite(BufferedOutput $outputLog)
    {
        if(DB::connection() instanceof SQLiteConnection) {
            $database = DB::connection()->getDatabaseName();
            if(!file_exists($database)) {
                touch($database);
                DB::reconnect(Config::get('database.default'));
            }
            $outputLog->write('Using SqlLite database: ' . $database, 1);
        }
    }
}
