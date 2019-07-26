<?php

namespace OxidCommunity\SecurityBundle\Security;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

class RequestMatcher implements RequestMatcherInterface
{

    /**
     * @var request_stack
     */
    private $routeLoader;

    public function __construct($routeLoader)
    {
        $this->routeLoader = $routeLoader;
    }

    public function matches(Request $request)
    {
        $url = $request->getPathInfo();

        $router = $this->routeLoader;
        $route = $router->match($request->getPathInfo());
        $route = $this->routeLoader->getRouteCollection()->get($route['_route']);

        return !empty($route->getOptions()['auth']);
    }
}