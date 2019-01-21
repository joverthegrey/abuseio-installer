<?php

namespace AbuseIO\AbuseIOInstaller\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class EnvironmentSaved
{
    use SerializesModels;

    private $request;

    /**
     * Create a new event instance.
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
