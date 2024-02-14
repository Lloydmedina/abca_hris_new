<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;

class UserAgent extends Controller
{
    public static function parseUserAgent($userAgentString)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgentString);

        $browser = $agent->browser();
        $platform = $agent->platform();
        $device = $agent->device();
        $isMobile = $agent->isMobile();
        $isDesktop = $agent->isDesktop();
        // You can extract more information as needed

        return (object) [
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
            'isMobile' => $isMobile,
            'isDesktop' => $isDesktop,
        ];
    }
}
