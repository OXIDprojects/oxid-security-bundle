<?php

namespace OxidCommunity\SecurityBundle\Security;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiFirewallMatcher implements RequestMatcherInterface
{
    public function matches(Request $request){
        $url = $request->getPathInfo();
        die("<pre>" . var_dump($request, true));
        $isMatch = strpos($url, "/api") === 0 && $request->get("isOnlineApp", false) === false;
        return $isMatch;
    }
}