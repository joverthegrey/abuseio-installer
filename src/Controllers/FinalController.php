<?php

namespace AbuseIO\AbuseIOInstaller\Controllers;

use Illuminate\Routing\Controller;
use AbuseIO\AbuseIOInstaller\Helpers\EnvironmentManager;
use AbuseIO\AbuseIOInstaller\Helpers\FinalInstallManager;
use AbuseIO\AbuseIOInstaller\Helpers\InstalledFileManager;
use AbuseIO\AbuseIOInstaller\Events\LaravelInstallerFinished;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param \AbuseIO\AbuseIOInstaller\Helpers\InstalledFileManager $fileManager
     * @param \AbuseIO\AbuseIOInstaller\Helpers\FinalInstallManager $finalInstall
     * @param \AbuseIO\AbuseIOInstaller\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);

        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
