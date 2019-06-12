<?php

namespace AbuseIO\AbuseIOInstaller\Controllers;

use AbuseIO\AbuseIOInstaller\Helpers\MigrationManager;
use http\Env\Request;
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
        if (isMigrated()) {
            $response = $this->migrationManager->startSeeding();
        }

        return view('vendor.installer.migrate');
    }

    /**
     * Update the admin user
     *
     * @param Request $request
     * @return mixed
     */
    public function addAdmin(Request $request)
    {
        $adminEmail = $request->session()->pull('admin_email', '');
        $adminPassword = $request->session()->pull('admin_password', '');

        if (isMigrated() && isSeeded()) {
            $response = $this->migrationManager->addAdmin($adminEmail, $adminPassword);
        }

        return view('vendor.installer.migrate');
    }

    public function getStatus()
    {
        return response()->json([
            'migrated' => isMigrated(),
            'seeded' => isSeeded(),
            'admin_created' => adminAdded(),
            'installed' => isInstalled(),
        ]);
    }
}
