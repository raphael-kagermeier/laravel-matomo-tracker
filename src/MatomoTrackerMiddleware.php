<?php

namespace Alfrasc\MatomoTracker;

use Closure;
use Illuminate\Http\Request;

class MatomoTrackerMiddleware
{
    use RequestHandlerTrait;

    /**
     * Handle an incoming request.
     *
     * track only page views that are not livewire internal requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // handle request data for better analytics
        $this->storeRequest($request);

        return $next($request);
    }
}
