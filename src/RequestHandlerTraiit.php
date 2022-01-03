<?php

namespace Alfrasc\MatomoTracker;

use Illuminate\Http\Request;

trait RequestHandlerTrait {

    /**
     * This method stores all the necessary data into the trackingData session array
     *
     * @param Request $request
     * @return void
     */
    public function storeRequest(Request $request):void
    {

        if(!$this->doNotTrack($request->path())){

            // set data that will be accessed when sending the request
            $request->trackingData('url', $request->fullUrl());
            $request->trackingData('urlReferrer',  $request->server('HTTP_REFERER'));
            $request->trackingData('pageName',  $request->route()?->getName()?? 'Name Not Set');
            $request->trackingData('currentTs',  time());
        }

    }

    /**
     * This excludes all requests that should not change the trackingData session array
     *
     * @param string $path
     * @return bool
     */
    public function doNotTrack(string $path):bool
    {

        // check if request should be tracked
        foreach (config('matomotracker.excluded_uris') as $uri) {
            if(str_contains($path, $uri)) return true;
        }

        // if it doesn't match
        return false;
    }

}
