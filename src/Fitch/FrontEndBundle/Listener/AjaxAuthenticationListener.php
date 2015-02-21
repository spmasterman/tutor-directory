<?php

namespace Fitch\FrontEndBundle\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AjaxAuthenticationListener
{
    /**
     * We're listening for kernel exceptions, and checking if the request was an Ajax request, and the exception
     * is Authentication or AccessDenied - if it is, then we create a new response, and send it in place of the
     * one Symfony would normally send (which would depending on the firewall/security settings) usually be a
     * login page. We definitely don't want to send bak the login page for Ajax Requests...
     *
     * In the FRONT END we can create a handler for ajaxError that reloads the page
     *
     * i.e.
     *  // reload when any ajax call fails (forces a bounce to login if we have expired session etc)
     *  $(document).ajaxError(function (event, jqXHR) {
     *    if (403 === jqXHR.status) {
     *    window.location.reload();
     *    }
     *  });
     *
     * This causes the page to reload, which will take the user to teh login screen. Neat-o.
     *
     * To see this behavior - clear:cache when a page is using...
     *      /Fitch/FrontEndBundle/Resources/assets/js/generic-div-ajax.js
     * ...to update the inline elements. The whole page should redirect to the login. If you disable this code, any
     * element that is being loaded via ajax, will instead get filled with the login page (i.e. broken)
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        $ajax = $request->isXmlHttpRequest();
        $exception = $event->getException();

        if (!$ajax ||
            (!$exception instanceof AuthenticationException && !$exception instanceof AccessDeniedException)) {
            return;
        }

        $response = new JsonResponse($exception->getMessage(), $exception->getCode());
        $event->setResponse($response);
        $event->stopPropagation();
    }
}
