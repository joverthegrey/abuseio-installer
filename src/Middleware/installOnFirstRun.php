<?php

namespace AbuseIO\AbuseIOInstaller\Middleware;

use Closure;

class installOnFirstRun
{
    // except on all paths starting with install
    protected $except = [
        '^install$',
        '^install/.+$'
    ];

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!$this->inExceptArray($request) && !isInstalled()) {
            return redirect('/install');
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {

            $pattern = ':' . $except . ':';
            $path = $request->path();

            if (preg_match($pattern, $path)) {
                return true;
            }
        }

        return false;
    }
}