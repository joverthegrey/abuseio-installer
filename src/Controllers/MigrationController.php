<?php

namespace AbuseIO\AbuseIOInstaller\Controllers;

use AbuseIO\AbuseIOInstaller\Helpers\MigrationManager;
use Illuminate\Routing\Controller;

class MigrationController extends Controller
{
    /**
     * @var MigrationManager
     */
    private $migrationManager;

    /**
     * @param MigrationManager $migrationManager
     */
    public function __construct(MigrationManager $migrationManager)
    {
        $this->migrationManager = $migrationManager;
    }

    /**
     * Run the migrations.
     *
     * @return \Illuminate\View\View
     */
    public function migrate()
    {
        $response = $this->migrationManager->startMigration();

        return view('vendor.installer.migrate');
    }

    /**
     * Seed the database.
     *
     * @return mixed
     */
    public function seed()
    {
        $response = $this->migrationManager->startSeeding();

        return view('vendor.installer.migrate');

    }
}
