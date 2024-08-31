<?php

namespace Inaam\Installer\Controllers;

use Illuminate\Routing\Controller;
use Inaam\Installer\Events\LaravelInstallerFinished;
use Inaam\Installer\Helpers\EnvironmentManager;
use Inaam\Installer\Helpers\FinalInstallManager;
use Inaam\Installer\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param \Inaam\Installer\Helpers\InstalledFileManager $fileManager
     * @param \Inaam\Installer\Helpers\FinalInstallManager $finalInstall
     * @param \Inaam\Installer\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(
        InstalledFileManager $fileManager,
        FinalInstallManager $finalInstall,
        EnvironmentManager $environment
    ) {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);

        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
