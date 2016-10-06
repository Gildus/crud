<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $header = $event->getResponse()->headers;
        $header->set('Access-Control-Allow-Headers', 'origin, content-type, accept, pagination');
        $header->set('Access-Control-Allow-Origin', '*');
        $header->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }
}