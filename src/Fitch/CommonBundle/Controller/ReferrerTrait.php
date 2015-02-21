<?php

namespace Fitch\CommonBundle\Controller;

trait ReferrerTrait
{
    /**
     * Apologies, referer is the term from the HTTP standard, but referrer is speeelled rite :).
     *
     * @return mixed
     */
    private function getReferrerRoute()
    {
        $request = $this->getRequest();

        //look for the referrer route
        $referrer = $request->headers->get('referer');
        $lastPath = substr($referrer, strpos($referrer, $request->getBaseUrl()));
        $lastPath = str_replace($request->getBaseUrl(), '', $lastPath);

        $matcher = $this->get('router')->getMatcher();
        $parameters = $matcher->match($lastPath);
        $route = $parameters['_route'];

        unset($parameters['_route']);
        unset($parameters['_controller']);

        return $this->generateUrl($route, $parameters);
    }
}
