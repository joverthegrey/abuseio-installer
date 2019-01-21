<?php

namespace AbuseIO\AbuseIOInstaller\Events;

use Illuminate\Queue\SerializesModels;

class LaravelInstallerFinished
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
